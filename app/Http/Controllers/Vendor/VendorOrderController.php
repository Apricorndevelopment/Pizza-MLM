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
use App\Models\PercentageRepurchaseIncome;
use App\Models\BonusIncome;
use App\Models\DirectIncome;
use App\Models\LevelIncome;
use App\Models\RepurchaseIncome;
use App\Models\CashbackIncome;
use App\Models\PercentageReward;
use App\Models\Vendor;
use App\Models\VendorIncome;
use Carbon\Carbon;

class VendorOrderController extends Controller
{
    public function index(Request $request)
    {
        // 1. Get Vendor ID from User
        $user = Auth::user();
        $vendor = \App\Models\Vendor::where('user_id', $user->id)->first();

        if (!$vendor) {
            if ($request->ajax()) {
                return response()->json(['data' => []]);
            }
            return view('vendor.orders.index', ['orders' => collect([])]);
        }

        $vendorId = $vendor->id;

        $query = Order::whereHas('items', function ($q) use ($vendorId) {
            $q->where('vendor_id', $vendorId);
        })
            ->with(['items' => function ($q) use ($vendorId) {
                $q->where('vendor_id', $vendorId);
            }, 'user']);

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_id', 'LIKE', "%{$search}%")
                    ->orWhere('status', 'LIKE', "%{$search}%")
                    ->orWhereHas('user', function ($u) use ($search) {
                        $u->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('email', 'LIKE', "%{$search}%")
                            ->orWhere('ulid', 'LIKE', "%{$search}%");
                    });
            });
        }

        $orders = $query->latest()->paginate(10)->withQueryString();

        $orders->getCollection()->transform(function ($order) {
            $order->vendor_total = $order->items->sum('subtotal');
            return $order;
        });

        if ($request->ajax()) {
            return response()->json($orders);
        }

        return view('vendor.orders.index', compact('orders'));
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'status'   => 'required|in:placed,accepted,delivered,rejected',
            'reason'   => 'nullable|string|required_if:status,rejected',
            'delivery_otp' => 'nullable|string|required_if:status,delivered',
        ]);

        $user = Auth::user();
        $vendor = Vendor::where('user_id', $user->id)->firstOrFail();
        $vendorId = $vendor->id;

        DB::beginTransaction();

        try {
            $order = Order::with('items', 'user')->lockForUpdate()->find($request->order_id);
            $orderUser = $order->user;

            if (!$order) {
                throw new \Exception("Order not found.");
            }

            $hasItems = $order->items()->where('vendor_id', $vendorId)->exists();

            if (!$hasItems) {
                return redirect()->back()->with('error', 'You are not authorized to update this order.');
            }

            // 1. REJECTED
            if ($request->status === 'rejected') {
                $this->processOrderRejection($order, $orderUser, $vendorId, $request->reason);
            }

            // 2. ACCEPTED (Trigger MLM Income Here)
            if ($request->status === 'accepted') {
                if ($order->status === 'placed') {
                    $this->processOrderAcceptance($order, $orderUser, $vendorId);
                }
            }

            // 3. DELIVERED (Only OTP & Vendor Payment)
            if ($request->status === 'delivered') {
                if (!$orderUser) {
                    throw new \Exception("User not found for this order.");
                }

                // Verify OTP
                if ($order->delivery_otp !== $request->delivery_otp) {
                    DB::rollBack();
                    return redirect()->back()->with('error', 'Invalid Delivery OTP! Cannot mark as delivered.');
                }

                // Pay Vendor (Income to upline already distributed on Accepted)
                $this->payVendorForOrder($order, $vendorId);
            }

            $order->status = $request->status;
            $order->save();

            DB::commit();

            return redirect()->back()->with('success', 'Order status updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Vendor Order Update Failed: " . $e->getMessage());
            return redirect()->back()->with('error', 'Error updating status: ' . $e->getMessage());
        }
    }

    // =========================================================================
    // MODULAR FUNCTIONS
    // =========================================================================

    private function processOrderRejection($order, $user, $vendorId, $reason)
    {
        OrderRejection::create([
            'order_id' => $order->id,
            'user_id'  => $vendorId,
            'reason'   => $reason
        ]);

        if ($user) {
            $user->wallet1_balance += $order->total_amount;
            $user->save();

            Wallet1Transaction::create([
                'user_id'   => $user->id,
                'user_ulid' => $user->ulid,
                'wallet1'   => $order->total_amount,
                'notes'     => 'Refund for Order Rejection (Order ID: ' . $order->order_id . ')',
                'balance'   => $user->wallet1_balance,
            ]);

            if ($order->coupons_used > 0) {
                $userCoupon = DB::table('user_coupons')->where('user_id', $user->id)->first();
                if ($userCoupon) {
                    DB::table('user_coupons')->where('user_id', $user->id)->increment('coupon_quantity', $order->coupons_used);
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
        $vendorEarnings = 0;
        $vendorTotalSales = 0;

        // लूप चलाकर हर आइटम का अलग-अलग कमीशन निकालेंगे
        foreach ($order->items as $item) {
            // सिर्फ इसी वेंडर के आइटम्स को चेक करेंगे
            if ($item->vendor_id == $vendorId) {

                // प्रोडक्ट टेबल से प्रोडक्ट को निकालें ताकि percentage मिल सके
                $product = Product::find($item->product_id);

                // अगर डेटाबेस में percentage नल (null) है, तो डिफ़ॉल्ट 30% एडमिन का मानेंगे
                $adminPercentage = ($product && $product->percentage !== null) ? $product->percentage : 30;

                // वेंडर का हिस्सा = 100 - एडमिन का हिस्सा
                $vendorPercentage = 100 - $adminPercentage;

                // इस आइटम की टोटल सेल (Price x Quantity)
                $itemTotal = $item->price * $item->quantity;
                $vendorTotalSales += $itemTotal;

                // वेंडर की कमाई में इसका हिस्सा जोड़ देंगे
                $vendorEarnings += $itemTotal * ($vendorPercentage / 100);
            }
        }

        if ($vendorEarnings > 0) {
            // वेंडर का यूजर अकाउंट ढूंढें
            $vendorUser = User::whereHas('vendor', function ($q) use ($vendorId) {
                $q->where('id', $vendorId);
            })->first();

            if ($vendorUser) {
                // वेंडर के वॉलेट में पैसे जोड़ें
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

    // Renamed to match logic
    private function processOrderAcceptance($order, $user, $vendorId)
    {
        $this->reduceProductStock($order);
        $totalPV = $this->calculateTotalPV($order);

        $settings = PercentageIncome::first();
        if (!$settings) {
            throw new \Exception("Percentage Income Settings not configured.");
        }

        if ($totalPV > 0) {

            // Logic: Check if it's the first purchase
            if ($user->is_paid == 0) {
                // First Time Purchase (Direct/Bonus Income will be distributed)
                $this->handleFirstPurchase($user, $order, $totalPV, $settings, $vendorId);

                // Mark user as Paid AND change Status to ACTIVE
                $user->is_paid = 1;
                $user->status = 'active';  // <--- YAHAN STATUS ACTIVE KIYA GAYA HAI
                $user->user_doa = now();
            } else {
                // Already Paid User (Repurchase Income)
                $this->handleUserRepurchase($user, $order, $totalPV, $settings, $vendorId);
            }

            $user->save();

            $this->processUplineGrowth($user, $totalPV, $settings);
            $this->distributeVendorIncome($vendorId, $order, $totalPV, $settings);
        }
    }

    private function distributeVendorIncome($vendorId, $order, $totalPV, $settings)
    {
        // Find user associated with vendor
        $vendorUser = User::whereHas('vendor', function ($q) use ($vendorId) {
            $q->where('id', $vendorId);
        })->first();

        if (!$vendorUser || !$vendorUser->sponsor_id) {
            return;
        }

        $vendorSponsor = User::where('ulid', $vendorUser->sponsor_id)->first();

        if ($vendorSponsor && $vendorSponsor->status === 'active' && $settings->vendor_income > 0) {
            $incomeAmount = $totalPV * ($settings->vendor_income / 100);

            $this->distributeToWallets($vendorSponsor, $incomeAmount, $settings, "Vendor Income from Vendor ID: {$vendorUser->ulid}");

            VendorIncome::create([
                'order_id'         => $order->id,
                'vendor_id'        => $vendorId,
                'admin_id'         => null,
                'user_id'          => $vendorSponsor->id,
                'user_ulid'        => $vendorSponsor->ulid,
                'from_vendor_name' => $vendorUser->name,
                'from_vendor_ulid' => $vendorUser->ulid,
                'purchase_amount'  => $order->total_amount,
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
                $product->decrement('stock_quantity', $item->quantity);
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

    // Renamed from handleUserActivation to handleFirstPurchase
    private function handleFirstPurchase($user, $order, $totalPV, $settings, $vendorId)
    {
        $this->distributeDirectIncome($user, $order, $totalPV, $settings, $vendorId);
        $this->distributeBonusIncome($user, $order, $totalPV, $settings, $vendorId);
        $this->distributeLevelIncome($user, $order, $totalPV, $settings, 'level_incomes', $vendorId);
    }

    private function handleUserRepurchase($user, $order, $totalPV, $settings, $vendorId)
    {
        $this->distributeLevelIncome($user, $order, $totalPV, $settings, 'repurchase_incomes', $vendorId);
        $this->distributeCashbackIncome($user, $order, $totalPV, $settings, $vendorId);
    }

    private function processUplineGrowth($startUser, $pv, $settings)
    {
        // 1. Update the Current User's Total Business (Self Business)
        $startUser->total_business += $pv;
        $startUser->save();

        // Check if the current user achieves a reward based on their own new total business
        $this->checkAndDistributeRewards($startUser, $milestones = PercentageReward::orderBy('achievement', 'asc')->get(), $settings);


        // 2. Update Upline's Total Business (Team Business)
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
        if ($user->status !== 'active') {
            return;
        }

        $receivedRewardIds = DB::table('rewards_incomes')->where('user_id', $user->id)->pluck('reward_id')->toArray();

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

    private function distributeDirectIncome($user, $order, $totalPV, $settings, $vendorId)
    {
        $sponsor = User::where('ulid', $user->sponsor_id)->first();

        if ($sponsor && $sponsor->status === 'active') {
            $incomeAmount = $totalPV * ($settings->direct_income / 100);

            if ($incomeAmount > 0) {
                $this->distributeToWallets($sponsor, $incomeAmount, $settings, "Direct Income from User: {$user->ulid}");

                DirectIncome::create([
                    'order_id'        => $order->id,
                    'vendor_id'       => $vendorId,
                    'admin_id'        => null,
                    'user_id'         => $sponsor->id,
                    'user_ulid'       => $sponsor->ulid,
                    'from_name'       => $user->name,
                    'from_ulid'       => $user->ulid,
                    'purchase_amount' => $order->total_amount,
                    'purchase_pv'     => $totalPV,
                    'income_amount'   => $incomeAmount,
                    'percentage'      => $settings->direct_income,
                ]);
            }
        }
    }

    private function distributeBonusIncome($user, $order, $totalPV, $settings, $vendorId)
    {
        $sponsor = User::where('ulid', $user->sponsor_id)->first();

        if ($sponsor && $sponsor->status === 'active') {
            $incomeAmount = $totalPV * ($settings->bonus_income / 100);

            if ($incomeAmount > 0) {
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
                    'order_id'        => $order->id,
                    'vendor_id'       => $vendorId,
                    'admin_id'        => null,
                    'user_id'         => $sponsor->id,
                    'user_ulid'       => $sponsor->ulid,
                    'from_name'       => $user->name,
                    'from_ulid'       => $user->ulid,
                    'purchase_amount' => $order->total_amount,
                    'purchase_pv'     => $totalPV,
                    'income_amount'   => $incomeAmount,
                    'percentage'      => $settings->bonus_income,
                ]);
            }
        }
    }

    private function distributeCashbackIncome($user, $order, $totalPV, $settings, $vendorId)
    {
        $incomeAmount = $totalPV * ($settings->cashback_income / 100);

        if ($incomeAmount > 0) {
            $this->distributeToWallets($user, $incomeAmount, $settings, "Cashback Income from Order #{$order->id}");

            CashbackIncome::create([
                'order_id'        => $order->id,
                'vendor_id'       => $vendorId,
                'admin_id'        => null,
                'user_id'         => $user->id,
                'user_ulid'       => $user->ulid,
                'purchase_amount' => $order->total_amount,
                'purchase_pv'     => $totalPV,
                'percentage'      => $settings->cashback_income,
                'income_amount'   => $incomeAmount,
            ]);
        }
    }

    private function distributeLevelIncome($fromUser, $order, $totalPV, $settings, $tableType, $vendorId)
    {
        if ($tableType === 'level_incomes') {
            $levelSettings = PercentageLevelIncome::orderBy('level', 'asc')->get();
        } else {
            $levelSettings = PercentageRepurchaseIncome::orderBy('level', 'asc')->get();
        }

        $currentUplineUlid = $fromUser->sponsor_id;

        foreach ($levelSettings as $levelSetting) {
            $uplineUser = User::where('ulid', $currentUplineUlid)->first();
            if (!$uplineUser) break;

            if ($uplineUser->status !== 'active' || $uplineUser->is_capping_enabled == 0) {
                $currentUplineUlid = $uplineUser->sponsor_id;
                continue;
            }

            // Capping Calculation
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
                        'order_id'         => $order->id,
                        'vendor_id'        => $vendorId,
                        'admin_id'         => null,
                        'user_id'          => $uplineUser->id,
                        'user_ulid'        => $uplineUser->ulid,
                        'from_user_id'     => $fromUser->id,
                        'from_user_ulid'   => $fromUser->ulid,
                        'from_user_name'   => $fromUser->name,
                        'purchase_amount'  => $order->total_amount,
                        'purchase_pv'      => $totalPV,
                        'level'            => $levelSetting->level,
                        'amount'           => $payableAmount,
                    ]);
                } else {
                    RepurchaseIncome::create([
                        'order_id'        => $order->id,
                        'vendor_id'       => $vendorId,
                        'admin_id'        => null,
                        'user_id'         => $uplineUser->id,
                        'from_ulid'       => $fromUser->ulid,
                        'from_name'       => $fromUser->name,
                        'purchase_amount' => $order->total_amount,
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
