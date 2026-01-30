<?php

namespace App\Services\Checkout\Pipes;

use App\Services\Checkout\CheckoutContext;
use App\Models\Wallet1Transaction;
use App\Models\Wallet2Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Closure;

class PersistOrder
{
    public function handle(CheckoutContext $context, Closure $next)
    {
        // 1. Deduct Wallet 1
        if ($context->wallet1Deduction > 0) {
            $context->user->wallet1_balance -= $context->wallet1Deduction;
            Wallet1Transaction::create([
                'user_id' => $context->user->id,
                'user_ulid' => $context->user->ulid,
                'wallet1' => -$context->wallet1Deduction,
                'notes' => 'Shop Purchase',
                'balance' => $context->user->wallet1_balance,
            ]);
        }

        // 2. Deduct Wallet 2
        if ($context->wallet2Deduction > 0) {
            $context->user->wallet2_balance -= $context->wallet2Deduction;
            Wallet2Transaction::create([
                'user_id' => $context->user->id,
                'user_ulid' => $context->user->ulid,
                'wallet2' => -$context->wallet2Deduction,
                'notes' => 'Shop Purchase Partial Payment',
                'balance' => $context->user->wallet2_balance,
            ]);
        }

        // 3. Deduct Coupons
        if ($context->couponsUsed > 0) {
            DB::table('user_coupons')
                ->where('user_id', $context->user->id)
                ->decrement('coupon_quantity', $context->couponsUsed);
        }

        $context->user->save();

        // 4. Create Order
        $orderId = 'ORD-' . strtoupper(Str::random(8));
        $orderID_DB = DB::table('orders')->insertGetId([
            'user_id' => $context->user->id,
            'order_id' => $orderId,
            'total_amount' => $context->totalDp,
            'wallet1_deducted' => $context->wallet1Deduction,
            'wallet2_deducted' => $context->wallet2Deduction,
            // You might need to add a 'coupon_discount' column to your orders table
            // 'coupon_discount' => $context->discountAmount,
            'coupons_used' => $context->couponsUsed,
            'status' => 'placed',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 5. Save Items
        foreach ($context->cartItems as $item) {
            // Remove the 'max_coupon_usage' key as it's not in the DB table
            unset($item['max_coupon_usage']); 
            
            $item['order_id'] = $orderID_DB;
            $item['created_at'] = now();
            $item['updated_at'] = now();
            DB::table('order_items')->insert($item);
        }

        // Pass the Order ID to the controller response
        $context->requestData['created_order_id'] = $orderId;

        return $next($context);
    }
}