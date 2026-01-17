<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Package1;
use App\Models\Vendor;
use App\Models\Wallet1Transaction;

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
        return view('vendors.dashboard');
    }
}