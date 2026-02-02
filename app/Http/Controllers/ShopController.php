<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Models\ProductPackage;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Pipeline\Pipeline; // Import Pipeline
use App\Services\Checkout\CheckoutContext; // Import Context

// Import Pipes
use App\Services\Checkout\Pipes\CalculateCartTotal;
use App\Services\Checkout\Pipes\ApplyCoupons;
use App\Services\Checkout\Pipes\ProcessPayment;
use App\Services\Checkout\Pipes\PersistOrder;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('search');

        $admin = Admin::first();
        // Admin Products
        $adminProducts = ProductPackage::query();
        if ($query) {
            $adminProducts->where('product_name', 'LIKE', "%{$query}%");
        }
        $adminProducts = $adminProducts->get();

        // Vendor Products (ONLY if vendor shop is open)
        $vendorProducts = Product::with('vendor')
            ->where('status', 'approved')
            ->whereHas('vendor', function ($q) {
                $q->where('isShopOpen', 1);
            });

        if ($query) {
            $vendorProducts->where('product_name', 'LIKE', "%{$query}%");
        }

        $vendorProducts = $vendorProducts->get();

        // User Coupon Count
        $userCoupon = DB::table('user_coupons')
            ->where('user_id', Auth::id())
            ->first();

        $userCouponCount = $userCoupon ? $userCoupon->coupon_quantity : 0;

        if ($request->ajax()) {
            return view(
                'user.shop.partials.products',
                compact('adminProducts', 'vendorProducts')
            )->render();
        }

        return view(
            'user.shop.index',
            compact('adminProducts', 'admin','vendorProducts', 'query', 'userCouponCount')
        );
    }


    public function purchase(Request $request)
    {
        $request->validate([
            'cart' => 'required|json',
            'wallet2_usage' => 'required|numeric|min:0',
            'coupons_used' => 'nullable|integer|min:0'
        ]);

        try {
            DB::beginTransaction();

            // 1. Create Context
            $context = new CheckoutContext(Auth::user(), $request->all());

            // 2. Define the Pipeline Steps (Order Matters!)
            $pipes = [
                CalculateCartTotal::class,
                ApplyCoupons::class,
                ProcessPayment::class,
                PersistOrder::class
            ];

            // 3. Execute Pipeline
            app(Pipeline::class)
                ->send($context)
                ->through($pipes)
                ->thenReturn();

            DB::commit();

            return redirect()->route('user.shop.index')
                ->with('success', 'Order Placed Successfully! Order ID: ' . $context->requestData['created_order_id']);
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Transaction Failed: ' . $e->getMessage());
        }
    }
}
