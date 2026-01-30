<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderRejection;
use App\Models\Wallet1Transaction; // Import Transaction Model
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminOrderController extends Controller
{
    /**
     * 1. Display a listing of orders (The Missing Method).
     */
    public function index(Request $request)
    {
        // Fetch Orders containing Admin products
        $query = Order::whereHas('items', function ($q) {
            $q->where('product_type', 'admin');
        })
        ->with(['user', 'items' => function ($q) {
            $q->where('product_type', 'admin');
        }]);

        // Search Filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_id', 'LIKE', "%{$search}%")
                  ->orWhere('status', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function($u) use ($search) {
                      $u->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        $orders = $query->latest()->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * 2. Update order status and handle refunds on rejection.
     */
    public function updateStatus(Request $request)
    {
        // Validate Input
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'status'   => 'required|in:placed,accepted,delivered,rejected',
            'reason'   => 'nullable|string|required_if:status,rejected',
        ]);

        $adminId = Auth::id();
        $order = Order::find($request->order_id);

        // Logic: Handle Rejection (Refunds & Logging)
        if ($request->status === 'rejected') {
            
            // A. Log the Rejection Reason
            OrderRejection::create([
                'order_id' => $order->id,
                'user_id'  => $adminId, 
                'reason'   => $request->reason
            ]);

            // --- REFUND LOGIC START ---
            $customer = $order->user;

            if ($customer) {
                // B. Refund Price to Wallet 1 Balance
                $customer->wallet1_balance += $order->total_amount;
                $customer->save();

                // Create Wallet 1 Transaction Log
                Wallet1Transaction::create([
                    'user_id'   => $customer->id,
                    'user_ulid' => $customer->ulid,
                    'wallet1'   => $order->total_amount,
                    'notes'     => 'Refund for Order Rejection (Order ID: ' . $order->order_id . ')',
                    'balance'   => $customer->wallet1_balance,
                ]);

                // C. Refund Coupons (If any were used)
                // Note: Ensure your column name matches DB (coupons_deducted vs coupons_used)
                if ($order->coupons_used > 0) {
                    
                    $userCoupon = DB::table('user_coupons')->where('user_id', $customer->id)->first();

                    if ($userCoupon) {
                        DB::table('user_coupons')
                            ->where('user_id', $customer->id)
                            ->increment('coupon_quantity', $order->coupons_used);
                    } else {
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

        // Update Status
        $order->status = $request->status;
        $order->save();

        return redirect()->back()->with('success', 'Order status updated successfully!');
    }
}