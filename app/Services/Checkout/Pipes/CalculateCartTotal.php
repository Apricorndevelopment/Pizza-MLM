<?php

namespace App\Services\Checkout\Pipes;

use App\Services\Checkout\CheckoutContext;
use App\Models\ProductPackage;
use App\Models\Product;
use Closure;

class CalculateCartTotal
{
    public function handle(CheckoutContext $context, Closure $next)
    {
        $rawCart = json_decode($context->requestData['cart'], true);
        
        if (empty($rawCart)) {
            throw new \Exception('Cart is empty');
        }

        foreach ($rawCart as $item) {
            // Fetch Product
            if ($item['type'] == 'admin') {
                $product = ProductPackage::find($item['id']);
                $vendorId = null;
            } else {
                $product = Product::find($item['id']);
                $vendorId = $product->vendor_id;
            }

            if (!$product) continue;

            $qty = (int) $item['qty'];
            $price = $product->dp;
            $lineTotal = $price * $qty;

            $context->totalDp += $lineTotal;

            // Store enriched data for later steps (Order Creation)
            $context->cartItems[] = [
                'product_type' => $item['type'],
                'product_id' => $product->id,
                'product_name' => $product->product_name,
                'product_image' => $product->product_image,
                'price' => $price,
                'quantity' => $qty,
                'subtotal' => $lineTotal,
                'vendor_id' => $vendorId,
                // CRITICAL FOR COUPONS: Pass the limit
                'max_coupon_usage' => $product->max_coupon_usage ?? 0 
            ];
        }

        // Initialize payable amount
        $context->payableAmount = $context->totalDp;

        return $next($context);
    }
}