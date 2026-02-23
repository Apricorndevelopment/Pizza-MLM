<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Coupon; // The model you provided
use App\Models\UserCoupon;
use App\Models\User;
use App\Models\Wallet1Transaction; // Assuming you track transactions

class CouponPurchaseController extends Controller
{
    /**
     * Display available coupon packages.
     */
    public function index()
    {
        // Fetch all packages from the coupons table
        $packages = Coupon::orderBy('created_at', 'desc')->get();
        return view('user.coupon-purchase', compact('packages'));
    }

    /**
     * Handle the purchase logic.
     */
    public function purchase(Request $request)
    {
        $request->validate([
            'package_id' => 'required|exists:coupons,id',
        ]);

        $user = Auth::user();
        $package = Coupon::findOrFail($request->package_id);

        // 1. Check Wallet Balance (Using Wallet 1)
        if ($user->wallet1_balance < $package->coupon_price) {
            return back()->with('error', 'Insufficient balance in Wallet 1. Required: ₹' . number_format($package->coupon_price, 2));
        }

        DB::beginTransaction();
        try {
            // 2. Deduct Money from Wallet 1
            $user->decrement('wallet1_balance', $package->coupon_price);

            // Refresh user to get updated balance for transaction record
            $user->refresh();

            // 3. Record Transaction
            Wallet1Transaction::create([
                'user_id'    => $user->id,
                'user_ulid'  => $user->ulid,
                'wallet1'    => -($package->coupon_price), 
                'balance'    => $user->wallet1_balance,
                'notes'      => "Purchased Coupon Package (Qty: {$package->coupon_qyt})",
            ]);

            // 4. Add Coupons to User
            $userCoupon = UserCoupon::where('user_id', $user->id)->first();

            if ($userCoupon) {
                // User already has coupons, just add quantity
                $userCoupon->increment('coupon_quantity', $package->coupon_qyt);
            } else {
                // First time user owning coupons
                UserCoupon::create([
                    'user_id' => $user->id,
                    'user_ulid' => $user->ulid,
                    'coupon_quantity' => $package->coupon_qyt,
                    'coupon_value' => 10.00 
                ]);
            }

            DB::commit();

            return back()->with('success', "Successfully purchased {$package->coupon_qyt} coupons for ₹{$package->coupon_price}!");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong. Please try again.');
        }
    }
}