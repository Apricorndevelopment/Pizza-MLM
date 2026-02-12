<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;

class CouponController extends Controller
{
    public function index()
    {
        // Fetch all coupons, latest first
        $coupons = Coupon::orderBy('created_at', 'desc')->get();
        return view('admin.coupons.index', compact('coupons'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'coupon_qyt' => 'required|integer|min:1',
            'coupon_price' => 'required|integer|min:1',
        ]);

        Coupon::create([
            'coupon_qyt' => $request->coupon_qyt,
            'coupon_price' => $request->coupon_price,
        ]);

        return redirect()->back()->with('success', 'Coupon batch added successfully!');
    }

    public function update(Request $request, $id)
    {
        $coupon = Coupon::findOrFail($id);

        $request->validate([
            'coupon_qyt' => 'required|integer|min:1',
            'coupon_price' => 'required|integer|min:1',
        ]);

        $coupon->update([
            'coupon_qyt' => $request->coupon_qyt,
            'coupon_price' => $request->coupon_price,
        ]);

        return redirect()->back()->with('success', 'Coupon batch updated successfully!');
    }

    public function destroy($id)
    {
        Coupon::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Coupon batch deleted successfully!');
    }
}
