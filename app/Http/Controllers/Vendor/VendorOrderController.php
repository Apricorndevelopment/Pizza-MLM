<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderRejection; // Ensure this model exists
use App\Models\Wallet1Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Needed for DB::table operations

class VendorOrderController extends Controller
{
    /**
     * Display a listing of the vendor's orders.
     */
    public function index(Request $request)
    {
        $vendorId = Auth::id();

        // 1. Fetch Orders that have items belonging to this vendor
        $query = Order::whereHas('items', function ($q) use ($vendorId) {
            $q->where('vendor_id', $vendorId);
        })
            // 2. Eager Load ONLY this vendor's items (Privacy Filter) & User info
            ->with(['items' => function ($q) use ($vendorId) {
                $q->where('vendor_id', $vendorId);
            }, 'user']);

        // 3. Search Filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_id', 'LIKE', "%{$search}%")
                    ->orWhere('status', 'LIKE', "%{$search}%");
            });
        }

        $orders = $query->latest()->paginate(10);

        // 4. Calculate 'Vendor Total' (Sum of this vendor's items only)
        $orders->getCollection()->transform(function ($order) {
            $order->vendor_total = $order->items->sum('subtotal');
            return $order;
        });

        return view('vendor.orders.index', compact('orders'));
    }

    /**
     * Update the order status and handle refunds if rejected.
     */
    public function updateStatus(Request $request)
    {
        // 1. Validate Input
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'status'   => 'required|in:placed,accepted,delivered,rejected',
            'reason'   => 'nullable|string|required_if:status,rejected',
        ]);

        $vendorId = Auth::id();
        $order = Order::find($request->order_id);

        // 2. Authorization Check: Ensure vendor actually owns items in this order
        $hasItems = $order->items()->where('vendor_id', $vendorId)->exists();

        if (! $hasItems) {
            return redirect()->back()->with('error', 'You are not authorized to update this order.');
        }

        // 3. Logic: Handle Rejection (Refunds & Logging)
        if ($request->status === 'rejected') {

            // A. Log the Rejection Reason
            OrderRejection::create([
                'order_id' => $order->id,
                'user_id'  => $vendorId, // The Vendor ID who rejected it
                'reason'   => $request->reason
            ]);

            // --- REFUND LOGIC START ---

            // Get the Customer (User) who placed the order
            $customer = $order->user;

            if ($customer) {
                // B. Refund Price to Wallet 2
                // Adding total_amount back to wallet2
                $customer->wallet1_balance += $order->total_amount;
                $customer->save();

                Wallet1Transaction::create([
                    'user_id' => $customer->id,
                    'user_ulid' => $customer->ulid,
                    'wallet1' => $order->total_amount,
                    'notes' => 'Refund for Order Rejection (Order ID: ' . $order->order_id . ')',
                    'balance' => $customer->wallet1_balance,
                ]);

                // C. Refund Coupons (If any were used)
                // Assumes you added 'coupons_deducted' column to orders table previously
                if ($order->coupons_used > 0) {

                    $userCoupon = DB::table('user_coupons')->where('user_id', $customer->id)->first();

                    if ($userCoupon) {
                        // Increment existing record
                        DB::table('user_coupons')
                            ->where('user_id', $customer->id)
                            ->increment('coupon_quantity', $order->coupons_used);
                    } else {
                        // Create new record if missing
                        DB::table('user_coupons')->insert([
                            'user_id' => $customer->id,
                            'coupon_quantity' => $order->coupons_used,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
            // --- REFUND LOGIC END ---
        }

        // 4. Update Order Status
        $order->status = $request->status;
        $order->save();

        return redirect()->back()->with('success', 'Order status updated successfully!');
    }
}
