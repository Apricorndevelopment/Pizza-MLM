<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Package1;
use App\Models\Product;
use App\Models\Vendor;
use App\Models\Wallet1Transaction;
use Carbon\Carbon;

class VendorController extends Controller
{
    // 1. "Become a Vendor" Front Page दिखाएं
    public function showPurchasePage()
    {
        $user = Auth::user();
        if ($user->is_vendor == 1) {
            return redirect()->route('vendor.dashboard');
        }

        // वेंडर पैकेज (ID 1)
        $package = Package1::find(1);
        return view('user.become-vendor', compact('package'));
    }

    // 2. पैकेज खरीदने का लॉजिक
    public function processPurchase(Request $request)
    {
        $user = Auth::user();
        $package = Package1::findOrFail(1); // मान रहे हैं ID 1 वेंडर पैकेज है

        if ($user->wallet1_balance < $package->price) {
            return back()->with('error', 'Insufficient Wallet Balance!');
        }

        DB::beginTransaction();
        try {
            // A. पैसा काटें
            $user->wallet1_balance -= $package->price;
            $user->is_vendor = 1; // यूजर अब वेंडर बन गया
            $user->save();

            // B. ट्रांजैक्शन हिस्ट्री
            Wallet1Transaction::create([
                'user_id' => $user->id,
                'user_ulid' => $user->ulid,
                'wallet1' => -$package->price,
                'notes' => 'Vendor Activation Package Purchased',
                'balance' => $user->wallet1_balance,
            ]);

            // C. Vendor Table में एंट्री
            Vendor::create([
                'user_id' => $user->id,
                'vendor_name' => $user->name,
                'company_name' => $request->company_name ?? $user->name . ' Store',
                'company_address' => $user->address,
                'company_city' => $request->city ?? $user->city,
                'company_state' => $user->state,
                'status' => 'vendor', // सीधे एक्टिव या पेंडिंग रख सकते हैं
            ]);

            DB::commit();
            return redirect()->route('vendor.dashboard')->with('success', 'Congratulations! You are now a Vendor.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // 3. Vendor Dashboard View
    public function dashboard()
    {
        $userId = Auth::id();

        // 1. Fetch Vendor Profile and Correct Vendor ID
        $vendor = \App\Models\Vendor::where('user_id', $userId)->first();

        // अगर किसी कारण से वेंडर प्रोफाइल नहीं बनी है, तो डैशबोर्ड क्रैश न हो इसलिए डिफ़ॉल्ट 0 भेजेंगे
        if (!$vendor) {
            return view('vendors.dashboard', [
                'isShopOpen' => false,
                'todaySales' => 0,
                'yesterdaySales' => 0,
                'monthlySales' => 0,
                'totalSales' => 0,
                'placedOrdersCount' => 0,
                'activeProducts' => 0,
                'lowStockProducts' => collect([]),
                'recentSales' => collect([])
            ]);
        }

        $vendorProfileId = $vendor->id; // <-- असली Vendor ID यहाँ से मिलेगी
        $isShopOpen = $vendor->isShopOpen;

        // --- SALES STATISTICS LOGIC ---

        // Base Query: अब हम सीधा order_items.vendor_id का इस्तेमाल कर रहे हैं
        $salesBaseQuery = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('order_items.vendor_id', $vendorProfileId) // FIXED: Using Vendor Table ID
            ->whereIn('orders.status', ['delivered', 'accepted']); // Revenue के लिए सिर्फ accepted/delivered लेना बेहतर है

        // A. Today's Sales
        $todaySales = (clone $salesBaseQuery)
            ->whereDate('orders.created_at', Carbon::today())
            ->sum('order_items.subtotal');

        // B. Yesterday's Sales
        $yesterdaySales = (clone $salesBaseQuery)
            ->whereDate('orders.created_at', Carbon::yesterday())
            ->sum('order_items.subtotal');

        // C. Monthly Sales (Current Month)
        $monthlySales = (clone $salesBaseQuery)
            ->whereMonth('orders.created_at', Carbon::now()->month)
            ->whereYear('orders.created_at', Carbon::now()->year)
            ->sum('order_items.subtotal');

        // D. Total Revenue (All time)
        $totalSales = (clone $salesBaseQuery)->sum('order_items.subtotal');

        $totalPayout = DB::table('vendor_wallet_transactions')
            ->where('user_id', $userId)
            ->where('notes', 'LIKE', 'Payment received for Order #%')
            ->sum('amount');

        // --- ORDER COUNTS ---

        // E. Currently Placed/Pending Orders
        $placedOrdersCount = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('order_items.vendor_id', $vendorProfileId) // FIXED
            ->where('orders.status', 'placed')
            ->distinct('orders.id')
            ->count('orders.id');

        // F. Active Products Count (यह User ID से ही लिंक रहता है, इसलिए इसे नहीं छेड़ा)
        $activeProducts = \App\Models\Product::where('vendor_user_id', $userId)
            ->where('status', 'approved')
            ->count();

        // G. Low Stock Products
        $lowStockProducts = \App\Models\Product::where('vendor_user_id', $userId)
            ->where('manage_stock', 1)
            ->where('stock_quantity', '<', 10)
            ->orderBy('stock_quantity', 'asc')
            ->limit(5)
            ->get();

        // H. Recent Sales Table
        $recentSales = (clone $salesBaseQuery)
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->select(
                'users.name as user_name',
                'users.email as user_email',
                'products.product_name',
                'order_items.quantity',
                'order_items.price',
                'orders.created_at'
            )
            ->orderBy('orders.created_at', 'desc')
            ->limit(10)
            ->get();

        return view('vendors.dashboard', compact(
            'isShopOpen',
            'todaySales',
            'yesterdaySales',
            'monthlySales',
            'totalSales',
            'totalPayout',
            'placedOrdersCount',
            'activeProducts',
            'lowStockProducts',
            'recentSales'
        ));
    }

    // FIXED TOGGLE METHOD (Returns JSON)
    public function toggleShopStatus(Request $request)
    {
        $request->validate([
            'status' => 'required|boolean'
        ]);

        try {
            $user = Auth::user();
            $vendor = Vendor::where('user_id', $user->id)->first();

            if (!$vendor) {
                return response()->json(['success' => false, 'message' => 'Vendor profile not found']);
            }

            $vendor->isShopOpen = $request->status;
            $vendor->save();

            return response()->json(['success' => true, 'isOpen' => $vendor->isShopOpen]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Show the Company Profile Page.
     */
    public function index()
    {
        // Fetch the vendor record associated with the logged-in user
        $vendor = Vendor::where('user_id', Auth::id())->firstOrFail();

        return view('vendor.profile.company', compact('vendor'));
    }

    /**
     * Update Company Details.
     */
    public function update(Request $request)
    {
        $request->validate([
            'company_name'   => 'required|string|max:255',
            'gst'            => 'nullable|string|max:20',
            'company_address' => 'required|string|max:500',
            'company_city'   => 'required|string|max:100',
            'company_state'  => 'required|string|max:100',
            'zip_code'       => 'required|string|max:10',
            'isShopOpen'     => 'required|boolean',
        ]);

        $vendor = Vendor::where('user_id', Auth::id())->firstOrFail();

        $vendor->update([
            'company_name'   => $request->company_name,
            'gst'            => $request->gst,
            'company_address' => $request->company_address,
            'company_city'   => $request->company_city,
            'company_state'  => $request->company_state,
            'zip_code'       => $request->zip_code,
            'isShopOpen'     => (int) $request->isShopOpen,
        ]);

        return back()->with('success', 'Company Profile Updated Successfully!');
    }
}
