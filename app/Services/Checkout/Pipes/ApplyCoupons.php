<?php

namespace App\Services\Checkout\Pipes;

use App\Services\Checkout\CheckoutContext;
use Illuminate\Support\Facades\DB;
use Closure;

class ApplyCoupons
{
    public function handle(CheckoutContext $context, Closure $next)
    {
        $requestedCoupons = (int) ($context->requestData['coupons_used'] ?? 0);

        if ($requestedCoupons > 0) {
            // 1. Calculate Max Allowed based on Cart Items
            $maxAllowedByCart = 0;
            foreach ($context->cartItems as $item) {
                // Logic: Qty * Max Coupon per item
                $maxAllowedByCart += ($item['quantity'] * $item['max_coupon_usage']);
            }

            if ($requestedCoupons > $maxAllowedByCart) {
                throw new \Exception("Coupon usage exceeds product limits. Max allowed: $maxAllowedByCart");
            }

            // 2. Check User Balance
            $userCouponData = DB::table('user_coupons')->where('user_id', $context->user->id)->first();
            $userBalance = $userCouponData ? $userCouponData->coupon_quantity : 0;

            if ($requestedCoupons > $userBalance) {
                throw new \Exception("Insufficient coupon balance.");
            }

            // 3. Apply Discount
            $discountValue = $requestedCoupons * 10; // 1 Coupon = 10 Rs
            
            // Prevent negative payable
            if ($discountValue > $context->payableAmount) {
                $discountValue = $context->payableAmount; 
            }

            $context->couponsUsed = $requestedCoupons;
            $context->discountAmount += $discountValue;
            $context->payableAmount -= $discountValue;
        }

        return $next($context);
    }
}