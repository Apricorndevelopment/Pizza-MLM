<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Models\ProductPackage;
use App\Models\Product;
use App\Models\Vendor;
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
        $user = Auth::user();

        // 1. Get current vendor ID to exclude own products
        $currentVendorId = null;
        if ($user->is_vendor == 1) {
            $vendor = Vendor::where('user_id', $user->id)->first();
            $currentVendorId = $vendor ? $vendor->id : null;
        }

        $admin = Admin::first();

        // 2. Admin Products Query
        $adminProductsQuery = ProductPackage::query();
        if ($query) {
            $adminProductsQuery->where('product_name', 'LIKE', "%{$query}%");
        }
        $adminProducts = $adminProductsQuery->paginate(8, ['*'], 'admin_page')->withQueryString();

        // 3. Vendor Products Query (Enhanced with Company Name Search)
        $vendorProductsQuery = Product::with('vendor')
            ->where('status', 'approved')
            ->whereHas('vendor', function ($q) {
                $q->where('isShopOpen', 1);
            });

        if ($currentVendorId) {
            $vendorProductsQuery->where('vendor_id', '!=', $currentVendorId);
        }

        if ($query) {
            $vendorProductsQuery->where(function ($q) use ($query) {
                // Match product name
                $q->where('product_name', 'LIKE', "%{$query}%")
                    // OR Match vendor company name
                    ->orWhereHas('vendor', function ($v) use ($query) {
                        $v->where('company_name', 'LIKE', "%{$query}%");
                    });
            });
        }

        $vendorProducts = $vendorProductsQuery->latest()->paginate(12, ['*'], 'vendor_page')->withQueryString();

        // 4. User Coupon Count
        $userCoupon = DB::table('user_coupons')
            ->where('user_id', Auth::id())
            ->first();

        $userCouponCount = $userCoupon ? $userCoupon->coupon_quantity : 0;

        if ($request->ajax()) {
            return view('user.shop.partials.products', compact('adminProducts', 'vendorProducts', 'admin'))->render();
        }

        return view('user.shop.index', compact('adminProducts', 'admin', 'vendorProducts', 'query', 'userCouponCount'));
    }


    public function purchase(Request $request)
    {
        $request->validate([
            'cart' => 'required|json',
            'cart' => 'required|json',
            'wallet2_usage' => 'required|numeric|min:0|max:' . Auth::user()->wallet2_balance,
            'coupons_used' => 'nullable|integer|min:0',
            // New Fields
            'phone_number' => 'required|string|max:15',
            'address'      => 'required|string|max:255',
            'location'     => 'required|string|max:100',
            'location'     => 'required|string|max:100',
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
