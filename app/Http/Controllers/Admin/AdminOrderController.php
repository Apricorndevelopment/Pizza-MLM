<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Models\OrderRejection;
use App\Models\Wallet1Transaction;
use App\Models\Wallet2Transaction;
use App\Models\ProductPackage;
use App\Models\User;
use App\Models\PercentageIncome;
use App\Models\PercentageLevelIncome;
use App\Models\BonusIncome;
use App\Models\DirectIncome;
use App\Models\LevelIncome;
use App\Models\RepurchaseIncome;
use App\Models\CashbackIncome;
use App\Models\PercentageReward;
use Carbon\Carbon;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::whereHas('items', function ($q) {
            $q->where('product_type', 'admin');
        })
            ->with(['user', 'items' => function ($q) {
                $q->where('product_type', 'admin');
            }]);

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_id', 'LIKE', "%{$search}%")
                    ->orWhere('status', 'LIKE', "%{$search}%")
                    ->orWhereHas('user', function ($u) use ($search) {
                        $u->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('email', 'LIKE', "%{$search}%");
                    });
            });
        }

        $orders = $query->latest()->paginate(10);

        if ($request->ajax()) {
            return response()->json($orders);
        }

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Main Method: Handles Status Updates safely.
     */
    public function updateStatus(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'status'   => 'required|in:placed,accepted,delivered,rejected',
            'reason'   => 'nullable|string|required_if:status,rejected',
        ]);

        $adminId = Auth::id();

        DB::beginTransaction();

        try {
            $order = Order::with('items', 'user')->lockForUpdate()->find($request->order_id);
            $user = $order->user;

            if (!$order) {
                throw new \Exception("Order not found.");
            }

            // 1. Handle Rejection
            if ($request->status === 'rejected') {
                $this->processOrderRejection($order, $user, $adminId, $request->reason);
            }

            // 2. Handle Delivery
            if ($request->status === 'delivered') {
                if (!$user) {
                    throw new \Exception("User not found for this order.");
                }
                $this->processOrderDelivery($order, $user);
            }

            // 3. Update Status
            $order->status = $request->status;
            $order->save();

            DB::commit();

            return redirect()->back()->with('success', 'Order status updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            dd('Error: ' . $e->getMessage(), 'Line: ' . $e->getLine());
            Log::error("Order Update Failed: " . $e->getMessage());
            return redirect()->back()->with('error', 'Error updating status: ' . $e->getMessage());
        }
    }

    // =========================================================================
    // MODULAR FUNCTIONS (PRIVATE)
    // =========================================================================

    private function processOrderRejection($order, $user, $adminId, $reason)
    {
        OrderRejection::create([
            'order_id' => $order->id,
            'user_id'  => $adminId,
            'reason'   => $reason
        ]);

        if ($user) {
            // Refund Money to Wallet 1
            $user->wallet1_balance += $order->total_amount;
            $user->save();

            Wallet1Transaction::create([
                'user_id'   => $user->id,
                'user_ulid' => $user->ulid,
                'wallet1'   => $order->total_amount,
                'notes'     => 'Refund for Order Rejection (Order ID: ' . $order->order_id . ')',
                'balance'   => $user->wallet1_balance,
            ]);

            // Refund Coupons
            if ($order->coupons_used > 0) {
                $userCoupon = DB::table('user_coupons')->where('user_id', $user->id)->first();
                if ($userCoupon) {
                    DB::table('user_coupons')
                        ->where('user_id', $user->id)
                        ->increment('coupon_quantity', $order->coupons_used);
                } else {
                    DB::table('user_coupons')->insert([
                        'user_id' => $user->id,
                        'coupon_quantity' => $order->coupons_used,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    private function processOrderDelivery($order, $user)
    {
        // --- STEP 1: Reduce Stock Quantity ---
        // This decreases the product stock based on the order quantity
        $this->reduceProductStock($order);

        // --- STEP 2: Check for Capping/Package Product ---
        foreach ($order->items as $item) {
            $product = ProductPackage::find($item->product_id);

            if ($product && $product->is_package_product == 1) {
                $user->capping_limit = $product->capping;
                $user->is_capping_enabled = 1;
                $user->save();
            }
        }

        // --- STEP 3: Calculate Total PV ---
        $totalPV = $this->calculateTotalPV($order);

        $settings = PercentageIncome::first();
        if (!$settings) {
            throw new \Exception("Percentage Income Settings not configured in Admin.");
        }

        if ($totalPV > 0) {
            if ($user->status == 'inactive') {
                $this->handleUserActivation($user, $order, $totalPV, $settings);
            } else {
                $this->handleUserRepurchase($user, $order, $totalPV, $settings);
            }

            $this->processUplineGrowth($user, $totalPV, $settings);
        }
    }

    /**
     * NEW FUNCTION: Reduces stock for products in the order
     */
    private function reduceProductStock($order)
    {
        foreach ($order->items as $item) {
            // Find the product package
            $product = ProductPackage::find($item->product_id);

            // Check if product exists and stock management is enabled
            if ($product && $product->manage_stock == 1) {

                // Ensure stock doesn't go below zero (optional check, but decrement handles logic)
                if ($product->stock_quantity >= $item->quantity) {
                    $product->decrement('stock_quantity', $item->quantity);
                } else {
                    // Logic if stock is insufficient (Optional: Force 0 or throw error)
                    // For now, we allow it to go to 0 or negative if needed, 
                    // or you can set it to 0 specifically.
                    // $product->update(['stock_quantity' => 0]); 
                    $product->decrement('stock_quantity', $item->quantity);
                }
            }
        }
    }

    private function calculateTotalPV($order)
    {
        $totalPV = 0;
        foreach ($order->items as $item) {
            $product = ProductPackage::find($item->product_id);
            if ($product) {
                $totalPV += ($product->pv * $item->quantity);
            }
        }
        return $totalPV;
    }

    private function handleUserActivation($user, $order, $totalPV, $settings)
    {
        // RULES APPLIED: Cashback removed from Activation (First Purchase)
        $this->distributeDirectIncome($user, $order->total_amount, $totalPV, $settings);
        $this->distributeBonusIncome($user, $order->total_amount, $totalPV, $settings);
        $this->distributeLevelIncome($user, $totalPV, $order->total_amount, $settings, 'level_incomes');

        $user->status = 'active';
        $user->user_doa = now();
        $user->save();
    }

    private function handleUserRepurchase($user, $order, $totalPV, $settings)
    {
        // 1. Repurchase Income
        $this->distributeLevelIncome($user, $totalPV, $order->total_amount, $settings, 'repurchase_incomes');

        // 2. Cashback Income
        $this->distributeCashbackIncome($user, $totalPV, $order->total_amount, $settings);
    }

    // -------------------------------------------------------------------------
    // NEW: UPLINE BUSINESS & REWARD LOGIC
    // -------------------------------------------------------------------------

    private function processUplineGrowth($startUser, $pv, $settings)
    {
        $currentUplineUlid = $startUser->sponsor_id;

        // Optimization: Fetch all milestones sorted by achievement ASC
        $milestones = PercentageReward::orderBy('achievement', 'asc')->get();

        while ($currentUplineUlid) {
            $upline = User::where('ulid', $currentUplineUlid)->first();

            // Chain breaks? Stop.
            if (!$upline) break;

            // 1. Update Total Business
            $upline->total_business += $pv;
            $upline->save();

            // 2. Check for Rewards
            $this->checkAndDistributeRewards($upline, $milestones, $settings);

            // Move Up
            $currentUplineUlid = $upline->sponsor_id;
        }
    }

    private function checkAndDistributeRewards($user, $milestones, $settings)
    {
        // RULES APPLIED: Only active users receive reward income
        if ($user->status !== 'active') {
            return;
        }

        $receivedRewardIds = DB::table('rewards_incomes')
            ->where('user_id', $user->id)
            ->pluck('reward_id')
            ->toArray();

        foreach ($milestones as $reward) {
            if ($user->total_business >= $reward->achievement && !in_array($reward->id, $receivedRewardIds)) {

                $this->distributeToWallets($user, $reward->reward, $settings, "Reward Achieved: {$reward->rank}");

                $user->current_rank = $reward->rank;
                $user->save();

                DB::table('rewards_incomes')->insert([
                    'user_id'            => $user->id,
                    'user_ulid'          => $user->ulid,
                    'rank_name'          => $reward->rank,
                    'reward_id'          => $reward->id,
                    'reward_amount'      => $reward->reward,
                    'reward_achivements' => $reward->achievement,
                    'created_at'         => now(),
                    'updated_at'         => now(),
                ]);
            }
        }
    }

    private function distributeDirectIncome($user, $purchaseAmount, $totalPV, $settings)
    {
        $sponsor = User::where('ulid', $user->sponsor_id)->first();

        // RULES APPLIED: Check if sponsor is active
        if ($sponsor && $sponsor->status === 'active') {
            $incomeAmount = $totalPV * ($settings->direct_income / 100);

            $this->distributeToWallets($sponsor, $incomeAmount, $settings, "Direct Income from User: {$user->ulid}");

            DirectIncome::create([
                'user_id'         => $sponsor->id,
                'user_ulid'       => $sponsor->ulid,
                'from_name'       => $user->name,
                'from_ulid'       => $user->ulid,
                'purchase_amount' => $purchaseAmount,
                'purchase_pv'     => $totalPV,
                'income_amount'   => $incomeAmount,
                'percentage'      => $settings->direct_income,
            ]);
        }
    }

    private function distributeBonusIncome($user, $purchaseAmount, $totalPV, $settings)
    {
        $sponsor = User::where('ulid', $user->sponsor_id)->first();

        // RULES APPLIED: Check if sponsor is active
        if ($sponsor && $sponsor->status === 'active') {
            $incomeAmount = $totalPV * ($settings->bonus_income / 100);

            // RULES APPLIED: 100% to Wallet 2 ONLY. No split distribution.
            $sponsor->wallet2_balance += $incomeAmount;
            $sponsor->save();

            Wallet2Transaction::create([
                'user_id'   => $sponsor->id,
                'user_ulid' => $sponsor->ulid,
                'wallet2'   => $incomeAmount,
                'notes'     => "Bonus Income from User: {$user->ulid} (100% Bonus Wallet)",
                'balance'   => $sponsor->wallet2_balance,
            ]);

            BonusIncome::create([
                'user_id'         => $sponsor->id,
                'user_ulid'       => $sponsor->ulid,
                'from_name'       => $user->name,
                'from_ulid'       => $user->ulid,
                'purchase_amount' => $purchaseAmount,
                'purchase_pv'     => $totalPV,
                'income_amount'   => $incomeAmount,
                'percentage'      => $settings->bonus_income,
            ]);
        }
    }

    private function distributeCashbackIncome($user, $totalPV, $purchaseAmount, $settings)
    {
        $incomeAmount = $totalPV * ($settings->cashback_income / 100);

        $this->distributeToWallets($user, $incomeAmount, $settings, "Cashback Income from Order #{$user->id}");

        CashbackIncome::create([
            'user_id'         => $user->id,
            'user_ulid'       => $user->ulid,
            'purchase_amount' => $purchaseAmount,
            'purchase_pv'     => $totalPV,
            'percentage'      => $settings->cashback_income,
            'income_amount'   => $incomeAmount,
        ]);
    }

    private function distributeLevelIncome($fromUser, $totalPV, $purchaseAmount, $settings, $tableType)
    {
        $levelSettings = PercentageLevelIncome::orderBy('level', 'asc')->get();
        $currentUplineUlid = $fromUser->sponsor_id;

        foreach ($levelSettings as $levelSetting) {
            $uplineUser = User::where('ulid', $currentUplineUlid)->first();

            if (!$uplineUser) break;

            // RULES APPLIED: Skip if upline is inactive OR capping is disabled
            if ($uplineUser->status !== 'active' || $uplineUser->is_capping_enabled == 0) {
                $currentUplineUlid = $uplineUser->sponsor_id;
                continue; // Moves to the next upline
            }

            $calculatedIncome = $totalPV * ($levelSetting->percentage / 100);
            $dailyLimit = $uplineUser->capping_limit;

            $todayLevelIncome = LevelIncome::where('user_id', $uplineUser->id)
                ->whereDate('created_at', Carbon::today())
                ->sum('amount');

            $todayRepurchaseIncome = RepurchaseIncome::where('user_id', $uplineUser->id)
                ->whereDate('created_at', Carbon::today())
                ->sum('commission');

            $totalEarnedToday = $todayLevelIncome + $todayRepurchaseIncome;
            $payableAmount = 0;

            if ($totalEarnedToday >= $dailyLimit) {
                $payableAmount = 0;
            } elseif (($totalEarnedToday + $calculatedIncome) > $dailyLimit) {
                $payableAmount = $dailyLimit - $totalEarnedToday;
            } else {
                $payableAmount = $calculatedIncome;
            }

            if ($payableAmount > 0) {
                $note = ($tableType == 'level_incomes')
                    ? "Level {$levelSetting->level} Income from {$fromUser->ulid}"
                    : "Repurchase Level {$levelSetting->level} Income from {$fromUser->ulid}";

                $this->distributeToWallets($uplineUser, $payableAmount, $settings, $note);

                if ($tableType == 'level_incomes') {
                    LevelIncome::create([
                        'user_id'         => $uplineUser->id,
                        'user_ulid'       => $uplineUser->ulid,
                        'from_user_id'    => $fromUser->id,
                        'from_user_ulid'  => $fromUser->ulid,
                        'from_user_name'  => $fromUser->name,
                        'purchase_amount' => $purchaseAmount,
                        'purchase_pv'     => $totalPV,
                        'level'           => $levelSetting->level,
                        'amount'          => $payableAmount,
                    ]);
                } else {
                    RepurchaseIncome::create([
                        'user_id'         => $uplineUser->id,
                        'from_ulid'       => $fromUser->ulid,
                        'from_name'       => $fromUser->name,
                        'purchase_amount' => $purchaseAmount,
                        'purchase_pv'     => $totalPV,
                        'commission'      => $payableAmount,
                        'level'           => $levelSetting->level,
                    ]);
                }
            }

            $currentUplineUlid = $uplineUser->sponsor_id;
        }
    }

    private function distributeToWallets($user, $amount, $settings, $note)
    {
        if ($amount <= 0) return;

        $wallet1Amount = $amount * ($settings->personal_wallet / 100);
        $wallet2Amount = $amount * ($settings->second_wallet / 100);

        $user->wallet1_balance += $wallet1Amount;
        $user->wallet2_balance += $wallet2Amount;
        $user->save();

        if ($wallet2Amount > 0) {
            Wallet2Transaction::create([
                'user_id'   => $user->id,
                'user_ulid' => $user->ulid,
                'wallet2'   => $wallet2Amount,
                'notes'     => $note . ' (W2)',
                'balance'   => $user->wallet2_balance,
            ]);
        }
    }
}
