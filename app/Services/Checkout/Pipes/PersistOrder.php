<?php

namespace App\Services\Checkout\Pipes;

use App\Services\Checkout\CheckoutContext;
use App\Models\Wallet1Transaction;
use App\Models\Wallet2Transaction;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Closure;

class PersistOrder
{
    public function handle(CheckoutContext $context, Closure $next)
    {
        // ====================================================
        // 1. RECORD TRANSACTIONS (Global Deduction)
        // ====================================================

        // Deduct Wallet 1
        if ($context->wallet1Deduction > 0) {
            $context->user->decrement('wallet1_balance', $context->wallet1Deduction);

            Wallet1Transaction::create([
                'user_id' => $context->user->id,
                'user_ulid' => $context->user->ulid,
                'wallet1' => -$context->wallet1Deduction,
                'notes' => 'Shop Purchase',
                'balance' => $context->user->wallet1_balance,
            ]);
        }

        // Deduct Wallet 2
        if ($context->wallet2Deduction > 0) {
            $context->user->decrement('wallet2_balance', $context->wallet2Deduction);

            Wallet2Transaction::create([
                'user_id' => $context->user->id,
                'user_ulid' => $context->user->ulid,
                'wallet2' => -$context->wallet2Deduction,
                'notes' => 'Shop Purchase Partial Payment',
                'balance' => $context->user->wallet2_balance,
            ]);
        }

        // Deduct Coupons
        if ($context->couponsUsed > 0) {
            DB::table('user_coupons')
                ->where('user_id', $context->user->id)
                ->decrement('coupon_quantity', $context->couponsUsed);
        }

        // ====================================================
        // 2. PREPARE ITEMS (Identify Vendors & Grouping)
        // ====================================================

        $cartItems = $context->cartItems;
        $itemsWithVendor = [];
        $totalCartValue = 0;

        foreach ($cartItems as $item) {
            $vendorId = null; // Default to Admin (null)

            // Identify Vendor ID
            if (isset($item['product_type']) && $item['product_type'] === 'vendor') {
                $product = Product::find($item['product_id']);
                if ($product) {
                    $vendorId = $product->vendor_id;
                }
            }

            $item['vendor_id'] = $vendorId;
            // Use 'quantity' as standardized in CalculateCartTotal
            $item['line_total'] = $item['price'] * $item['quantity'];

            $itemsWithVendor[] = $item;
            $totalCartValue += $item['line_total'];
        }

        // Group items by Vendor ID
        $groupedItems = collect($itemsWithVendor)->groupBy('vendor_id');
        $createdOrderIds = [];

        // ====================================================
        // 3. CREATE SPLIT ORDERS (Per Vendor)
        // ====================================================

        foreach ($groupedItems as $vendorId => $items) {

            // FIX: PHP array keys cast NULL to "", so we must convert it back to NULL
            // This prevents "Incorrect integer value: '' for column 'vendor_id'"
            if ($vendorId === "") {
                $vendorId = null;
            }

            // A. Calculate Proportions
            $orderSubtotal = $items->sum('line_total');
            $ratio = ($totalCartValue > 0) ? ($orderSubtotal / $totalCartValue) : 0;

            $orderWallet1 = round($context->wallet1Deduction * $ratio, 2);
            $orderWallet2 = round($context->wallet2Deduction * $ratio, 2);

            // B. Generate Unique ID
            $orderStringId = null;
            do {
                // Example: ORD-20231025-ABCD12
                $orderStringId = 'ORD-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
            } while (DB::table('orders')->where('order_id', $orderStringId)->exists());

            // Generate a 6-digit random OTP
            $deliveryOtp = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);

            // C. Create Order Record
            $orderIdDb = DB::table('orders')->insertGetId([
                'user_id' => $context->user->id,
                'order_id' => $orderStringId,
                'vendor_id' => $vendorId, // Null for Admin, ID for Vendors

                'total_amount' => $orderSubtotal,
                'wallet1_deducted' => $orderWallet1,
                'wallet2_deducted' => $orderWallet2,
                'coupons_used' => 0, // Proportional logic excluded for simplicity

                'status' => 'placed',
                'delivery_otp' => $deliveryOtp,

                // Contact Details from Request
                'phone_number' => $context->requestData['phone_number'] ?? null,
                'address'      => $context->requestData['address'] ?? null,
                'location'     => $context->requestData['location'] ?? null,
                // orders table mein insert karte waqt
                'total_profit' => $items->sum(function ($i) {
                    return $i['profit_per_unit'] * $i['quantity'];
                }),

                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $createdOrderIds[] = $orderStringId;

            // D. Save Items for this Order
            foreach ($items as $item) {
                DB::table('order_items')->insert([
                    'order_id' => $orderIdDb,
                    'product_id' => $item['product_id'],
                    'product_name' => $item['product_name'],

                    // Added Missing Columns
                    'product_image' => $item['product_image'] ?? null,
                    'vendor_id' => $vendorId,
                    'product_type' => $item['product_type'],

                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['line_total'],

                    // --- NEW: Storing Profit ---
                    // Yahan item ka profit store ho raha hai taaki revenue report mein kaam aaye
                    'profit' => $item['profit_per_unit'] * $item['quantity'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // ====================================================
        // 4. FINALIZE
        // ====================================================

        $context->requestData['created_order_id'] = implode(', ', $createdOrderIds);

        return $next($context);
    }
}
