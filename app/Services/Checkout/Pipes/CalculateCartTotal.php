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

            // --- GST CALCULATION LOGIC START ---
            $basePrice = $product->dp; // Dealer Price
            $gstPercent = $product->gst ?? 0; // Get GST % from DB or 0

            // Calculate GST Amount per unit
            $gstAmountPerUnit = ($basePrice * $gstPercent) / 100;

            // Final Unit Price (Inclusive of GST)
            $unitPriceWithTax = $basePrice + $gstAmountPerUnit;

            // Total Line Amount
            $lineTotal = $unitPriceWithTax * $qty;
            // --- GST CALCULATION LOGIC END ---

            // --- NEW: Profit calculation ---
            // Ek unit ka profit fetch kar rahe hain
            $unitProfit = $product->profit ?? 0;
            $totalProfitForLine = $unitProfit * $qty;

            $context->totalDp += $lineTotal;

            $context->cartItems[] = [
                'product_type' => $item['type'],
                'product_id' => $product->id,
                'product_name' => $product->product_name,
                'product_image' => $product->product_image,
                'price' => $unitPriceWithTax,
                'quantity' => $qty,
                'subtotal' => $lineTotal,
                'vendor_id' => $vendorId,
                'max_coupon_usage' => $product->max_coupon_usage ?? 0,
                'profit_per_unit' => $unitProfit, // Profit enriched here
            ];
        }

        // Initialize payable amount with the Tax Inclusive Total
        $context->payableAmount = $context->totalDp;

        return $next($context);
    }
}
