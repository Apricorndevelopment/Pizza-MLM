<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Models\Order;
use App\Models\OrderRejection;
use App\Models\Product;
use App\Models\User;
use App\Models\Wallet1Transaction;
use App\Models\Wallet2Transaction;

use App\Models\PercentageIncome;
use App\Models\PercentageLevelIncome;
use App\Models\BonusIncome;
use App\Models\DirectIncome;
use App\Models\LevelIncome;
use App\Models\RepurchaseIncome;
use App\Models\CashbackIncome;
use App\Models\PercentageReward;
use App\Models\VendorIncome;

class VendorOrderController extends Controller
{
    /**
     * Display a listing of the vendor's orders.
     */
    public function index(Request $request)
    {
        $vendorId = Auth::id();

        // 1. Fetch Orders that have items belonging to this vendor
        $query = Order::whereHas('items', function ($q) use ($vendorId) {
            $q->where('vendor_id', $vendorId);
        })
            ->with(['items' => function ($q) use ($vendorId) {
                $q->where('vendor_id', $vendorId);
            }, 'user']);

        // 2. Search Filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_id', 'LIKE', "%{$search}%")
                    ->orWhere('status', 'LIKE', "%{$search}%");
            });
        }

        $orders = $query->latest()->paginate(10);

        // 3. Calculate 'Vendor Total' (Sum of this vendor's items only)
        $orders->getCollection()->transform(function ($order) {
            $order->vendor_total = $order->items->sum('subtotal');
            return $order;
        });

        return view('vendor.orders.index', compact('orders'));
    }

    /**
     * Update the order status and handle refunds or income distribution.
     */
    public function updateStatus(Request $request)
    {
        // 1. Validate Input
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'status'   => 'required|in:placed,accepted,delivered,rejected',
            'reason'   => 'nullable|string|required_if:status,rejected',
        ]);

        $vendorId = Auth::id();

        DB::beginTransaction();

        try {
            // Lock for update to prevent race conditions
            $order = Order::with('items', 'user')->lockForUpdate()->find($request->order_id);
            $user = $order->user;

            if (!$order) {
                throw new \Exception("Order not found.");
            }

            // 2. Authorization Check: Ensure vendor actually owns items in this order
            $hasItems = $order->items()->where('vendor_id', $vendorId)->exists();

            if (!$hasItems) {
                return redirect()->back()->with('error', 'You are not authorized to update this order.');
            }

            // 3. Handle Rejection (Refunds)
            if ($request->status === 'rejected') {
                $this->processOrderRejection($order, $user, $vendorId, $request->reason);
            }

            // 4. Handle Delivery (Income Distribution + Stock Reduction)
            if ($request->status === 'delivered') {
                if (!$user) {
                    throw new \Exception("User not found for this order.");
                }
                // Process Income Distribution & Stock Updates
                $this->processOrderDelivery($order, $user, $vendorId);

                // B. Pay the Vendor
                $this->payVendorForOrder($order, $vendorId);
            }

            // 5. Update Order Status
            $order->status = $request->status;
            $order->save();

            DB::commit(); // Commit Transaction

            return redirect()->back()->with('success', 'Order status updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback on error
            Log::error("Vendor Order Update Failed: " . $e->getMessage());
            return redirect()->back()->with('error', 'Error updating status: ' . $e->getMessage());
        }
    }

    // =========================================================================
    // MODULAR FUNCTIONS (PRIVATE) - Same logic as Admin, but Product model differs
    // =========================================================================

    private function processOrderRejection($order, $user, $vendorId, $reason)
    {
        OrderRejection::create([
            'order_id' => $order->id,
            'user_id'  => $vendorId,
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

    private function payVendorForOrder($order, $vendorId)
    {
        // Calculate total amount for this specific vendor from the order items
        $vendorEarnings = $order->items()
            ->where('vendor_id', $vendorId)
            ->sum(DB::raw('price * quantity')); 

        if ($vendorEarnings > 0) {
            $vendorUser = User::find($vendorId);

            if ($vendorUser) {
                $vendorUser->wallet1_balance += $vendorEarnings;
                $vendorUser->save();

                Wallet1Transaction::create([
                    'user_id'   => $vendorUser->id,
                    'user_ulid' => $vendorUser->ulid,
                    'wallet1'   => $vendorEarnings,
                    'notes'     => "Payment received for Order #{$order->order_id}",
                    'balance'   => $vendorUser->wallet1_balance,
                ]);
            }
        }
    }

    private function processOrderDelivery($order, $user, $vendorId)
    {
        // --- STEP 1: Reduce Stock Quantity ---
        $this->reduceProductStock($order);

        // --- STEP 2: Calculate Total PV ---
        $totalPV = $this->calculateTotalPV($order);

        $settings = PercentageIncome::first();
        if (!$settings) {
            throw new \Exception("Percentage Income Settings not configured.");
        }

        if ($totalPV > 0) {
            if ($user->status == 'inactive') {
                $this->handleUserActivation($user, $order, $totalPV, $settings);
            } else {
                $this->handleUserRepurchase($user, $order, $totalPV, $settings);
            }

            // Process Upline Business Growth & Rewards
            $this->processUplineGrowth($user, $totalPV, $settings);

            // --- STEP 3: DISTRIBUTE VENDOR INCOME ---
            $this->distributeVendorIncome($vendorId, $totalPV, $order->total_amount, $settings);
        }
    }

    private function distributeVendorIncome($vendorId, $totalPV, $purchaseAmount, $settings)
    {
        $vendorUser = User::find($vendorId);
        
        if (!$vendorUser || !$vendorUser->sponsor_id) {
            return; 
        }

        $vendorSponsor = User::where('ulid', $vendorUser->sponsor_id)->first();

        // RULES APPLIED: Sponsor must be 'active' to receive Vendor Income
        if ($vendorSponsor && $vendorSponsor->status === 'active' && $settings->vendor_income > 0) {
            
            $incomeAmount = $totalPV * ($settings->vendor_income / 100);

            $this->distributeToWallets($vendorSponsor, $incomeAmount, $settings, "Vendor Income from Vendor ID: {$vendorUser->ulid}");

            VendorIncome::create([
                'user_id'          => $vendorSponsor->id,
                'user_ulid'        => $vendorSponsor->ulid,
                'from_vendor_name' => $vendorUser->name,
                'from_vendor_ulid' => $vendorUser->ulid,
                'purchase_amount'  => $purchaseAmount,
                'purchase_pv'      => $totalPV,
                'income_amount'    => $incomeAmount,
                'percentage'       => $settings->vendor_income,
            ]);
        }
    }

    private function reduceProductStock($order)
    {
        foreach ($order->items as $item) {
            $product = Product::find($item->product_id);

            if ($product && $product->manage_stock == 1) {
                if ($product->stock_quantity >= $item->quantity) {
                    $product->decrement('stock_quantity', $item->quantity);
                } else {
                    $product->decrement('stock_quantity', $item->quantity);
                }
            }
        }
    }

    private function calculateTotalPV($order)
    {
        $totalPV = 0;
        foreach ($order->items as $item) {
            $product = Product::find($item->product_id);
            if ($product) {
                $totalPV += ($product->pv * $item->quantity);
            }
        }
        return $totalPV;
    }

    private function handleUserActivation($user, $order, $totalPV, $settings)
    {
        // 1. Direct Income
        $this->distributeDirectIncome($user, $order->total_amount, $totalPV, $settings);
        
        // 2. Bonus Income (To Wallet 2 only)
        $this->distributeBonusIncome($user, $order->total_amount, $totalPV, $settings);

        // 3. Level Income
        $this->distributeLevelIncome($user, $totalPV, $order->total_amount, $settings, 'level_incomes');

        // RULES APPLIED: Cashback is removed from Activation (Only given on Repurchase)

        // 4. Activate User
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
    // UPLINE BUSINESS & REWARD LOGIC
    // -------------------------------------------------------------------------

    private function processUplineGrowth($startUser, $pv, $settings)
    {
        $currentUplineUlid = $startUser->sponsor_id;
        $milestones = PercentageReward::orderBy('achievement', 'asc')->get();

        while ($currentUplineUlid) {
            $upline = User::where('ulid', $currentUplineUlid)->first();
            if (!$upline) break;

            $upline->total_business += $pv;
            $upline->save();

            $this->checkAndDistributeRewards($upline, $milestones, $settings);

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

    // -------------------------------------------------------------------------
    // DISTRIBUTOR HELPERS
    // -------------------------------------------------------------------------

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

            // RULES APPLIED: Skip if upline is inactive
            if ($uplineUser->status !== 'active') {
                $currentUplineUlid = $uplineUser->sponsor_id;
                continue; // Move to the next upline
            }

            $incomeAmount = $totalPV * ($levelSetting->percentage / 100);
            $note = ($tableType == 'level_incomes')
                ? "Level {$levelSetting->level} Income from {$fromUser->ulid}"
                : "Repurchase Level {$levelSetting->level} Income from {$fromUser->ulid}";

            $this->distributeToWallets($uplineUser, $incomeAmount, $settings, $note);

            if ($tableType == 'level_incomes') {
                LevelIncome::create([
                    'user_id'          => $uplineUser->id,
                    'user_ulid'        => $uplineUser->ulid,
                    'from_user_id'     => $fromUser->id,
                    'from_user_ulid'   => $fromUser->ulid,
                    'from_user_name'   => $fromUser->name,
                    'purchase_amount'  => $purchaseAmount,
                    'purchase_pv'      => $totalPV,
                    'level'            => $levelSetting->level,
                    'amount'           => $incomeAmount,
                ]);
            } else {
                RepurchaseIncome::create([
                    'user_id'         => $uplineUser->id,
                    'from_ulid'       => $fromUser->ulid,
                    'from_name'       => $fromUser->name,
                    'purchase_amount' => $purchaseAmount,
                    'purchase_pv'     => $totalPV,
                    'commission'      => $incomeAmount,
                    'level'           => $levelSetting->level,
                ]);
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

        // FIX: Added missing Wallet 1 Transaction
        if ($wallet1Amount > 0) {
            Wallet1Transaction::create([
                'user_id'   => $user->id,
                'user_ulid' => $user->ulid,
                'wallet1'   => $wallet1Amount,
                'notes'     => $note . ' (W1)',
                'balance'   => $user->wallet1_balance,
            ]);
        }

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