<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BonusIncome;
use App\Models\DirectIncome;
use App\Models\LevelIncome;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderRejection;
use App\Models\PercentageIncome;
use App\Models\PercentageLevelIncome;
use App\Models\ProductPackage;
use App\Models\RepurchaseIncome;
use App\Models\User;
use App\Models\Wallet1Transaction;
use App\Models\Wallet2Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminOrderController extends Controller
{
    /**
     * 1. Display a listing of orders (The Missing Method).
     */
    public function index(Request $request)
    {
        // Fetch Orders containing Admin products
        $query = Order::whereHas('items', function ($q) {
            $q->where('product_type', 'admin');
        })
            ->with(['user', 'items' => function ($q) {
                $q->where('product_type', 'admin');
            }]);

        // Search Filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_id', 'LIKE', "%{$search}%")
                    ->orWhere('status', 'LIKE', "%{$search}%")
                    ->orWhereHas('user', function ($u) use ($search) {
                        $u->where('name', 'LIKE', "%{$search}%");
                    });
            });
        }

        $orders = $query->latest()->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * 2. Update order status and handle refunds on rejection.
     */
    public function updateStatus(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'status'   => 'required|in:placed,accepted,delivered,rejected',
            'reason'   => 'nullable|string|required_if:status,rejected',
        ]);

        $adminId = Auth::id();

        // Start Transaction
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

            // 2. Handle Delivery (Income Distribution)
            if ($request->status === 'delivered') {
                if (!$user) {
                    throw new \Exception("User not found for this order.");
                }
                $this->processOrderDelivery($order, $user);
            }

            // 3. Update Status
            $order->status = $request->status;
            $order->save();

            // All Good? Commit!
            DB::commit();

            return redirect()->back()->with('success', 'Order status updated successfully!');
        } catch (\Exception $e) {
            // Something went wrong? Rollback everything!
            DB::rollBack();

            // Log the error for debugging
            Log::error("Order Update Failed: " . $e->getMessage());

            return redirect()->back()->with('error', 'Error updating status: ' . $e->getMessage());
        }
    }

    // =========================================================================
    // MODULAR FUNCTIONS (PRIVATE)
    // =========================================================================

    /**
     * Handle the logic for Rejecting an order (Refunds).
     */
    private function processOrderRejection($order, $user, $adminId, $reason)
    {
        // Log Rejection
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

    /**
     * Handle the logic for Delivering an order (Income Calculations).
     */
    private function processOrderDelivery($order, $user)
    {
        // 1. Calculate Total PV
        $totalPV = $this->calculateTotalPV($order);

        // 2. Fetch Global Settings
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
        }
    }

    /**
     * Calculate Total PV from Order Items
     */
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

    /**
     * Scenario A: First Purchase (Activation) Logic
     */
    private function handleUserActivation($user, $order, $totalPV, $settings)
    {
        // 1. Direct Income
        $this->distributeDirectIncome($user, $order->total_amount, $totalPV, $settings);

        // 2. Level Income
        $this->distributeLevelIncome($user, $totalPV, $order->total_amount, $settings, 'level_incomes');

        // 3. Bonus Income
        $this->distributeBonusIncome($user, $totalPV, $order->total_amount, $settings);

        // 4. Activate User
        $user->status = 'active';
        $user->user_doa = now();
        $user->save();
    }

    /**
     * Scenario B: Repurchase Logic
     */
    private function handleUserRepurchase($user, $order, $totalPV, $settings)
    {
        // 1. Repurchase Income
        $this->distributeLevelIncome($user, $totalPV, $order->total_amount, $settings, 'repurchase_incomes');

        // 2. Bonus Income
        $this->distributeBonusIncome($user, $totalPV, $order->total_amount, $settings);
    }

    /**
     * Distribute Direct Income to Sponsor
     */
    private function distributeDirectIncome($user, $purchaseAmount, $totalPV, $settings)
    {
        $sponsor = User::where('ulid', $user->sponsor_id)->first();

        if ($sponsor) {
            $incomeAmount = $totalPV * ($settings->direct_income / 100);

            // Credit Wallets
            $this->distributeToWallets($sponsor, $incomeAmount, $settings, "Direct Income from User: {$user->ulid}");

            // Log Record
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

    /**
     * Distribute Bonus Income to Self
     */
    private function distributeBonusIncome($user, $totalPV, $purchaseAmount, $settings)
    {
        $incomeAmount = $totalPV * ($settings->bonus_income / 100);

        // Credit Wallets
        $this->distributeToWallets($user, $incomeAmount, $settings, "Bonus Income from Order #{$user->id}");

        // Log Record
        BonusIncome::create([
            'user_id'         => $user->id,
            'user_ulid'       => $user->ulid,
            'purchase_amount' => $purchaseAmount,
            'purchase_pv'     => $totalPV,
            'percentage'      => $settings->bonus_income,
            'income_amount'   => $incomeAmount,
        ]);
    }

    /**
     * Distribute Level / Repurchase Income to Upline
     */
    private function distributeLevelIncome($fromUser, $totalPV, $purchaseAmount, $settings, $tableType)
    {
        $levelSettings = PercentageLevelIncome::orderBy('level', 'asc')->get();

        if ($levelSettings->isEmpty()) {
            // Optional: Throw error if level settings are missing?
            // throw new \Exception("Percentage Level Income settings missing."); 
            return;
        }

        $currentUplineUlid = $fromUser->sponsor_id;

        foreach ($levelSettings as $levelSetting) {
            $uplineUser = User::where('ulid', $currentUplineUlid)->first();

            if (!$uplineUser) break; // Stop if no upline found

            $incomeAmount = $totalPV * ($levelSetting->percentage / 100);
            $note = ($tableType == 'level_incomes')
                ? "Level {$levelSetting->level} Income from {$fromUser->ulid}"
                : "Repurchase Level {$levelSetting->level} Income from {$fromUser->ulid}";

            // Credit Wallets
            $this->distributeToWallets($uplineUser, $incomeAmount, $settings, $note);

            // Log Record based on type
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
                    'amount'          => $incomeAmount,
                ]);
            } else {
                RepurchaseIncome::create([
                    'user_id'         => $uplineUser->ulid, // Schema uses varchar user_id here? Check DB.
                    'from_ulid'       => $fromUser->ulid,
                    'from_name'       => $fromUser->name,
                    'purchase_amount' => $purchaseAmount,
                    'purchase_pv'     => $totalPV,
                    'commission'      => $incomeAmount,
                    'level'           => $levelSetting->level,
                ]);
            }

            // Move Up
            $currentUplineUlid = $uplineUser->sponsor_id;
        }
    }

    /**
     * Helper to Split Money into Wallets based on PercentageIncome settings
     */
    private function distributeToWallets($user, $amount, $settings, $note)
    {
        if ($amount <= 0) return;

        $wallet1Amount = $amount * ($settings->personal_wallet / 100);
        $wallet2Amount = $amount * ($settings->second_wallet / 100);

        // Update User Model (Memory)
        $user->wallet1_balance += $wallet1Amount;
        $user->wallet2_balance += $wallet2Amount;
        $user->save(); // Save to DB


    }
}
