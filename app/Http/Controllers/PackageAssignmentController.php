<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Package1;
use App\Models\Package2Purchase;
use App\Models\User;
use App\Models\PackageTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PackageAssignmentController extends Controller
{
    public function index()
    {
        return view('admin.packagesassign');
    }

    public function search(Request $request)
    {
        $request->validate(['ulid' => 'required|string']);

        $user = User::where('ulid', $request->ulid)->first();

        if (!$user) {
            return back()->with('error', 'User not found with this ULID');
        }

        $packages = Package1::all();

        return view('admin.packagesassign',compact('user' , 'packages') );
    }

    public function assignPackage(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'package_id' => 'required|exists:package1,id',
        ]);

        $user = User::findOrFail($request->user_id);
        $package = Package1::findOrFail($request->package_id);

        // Calculate final price
        $discountAmount = $package->discount_per ? ($package->price * $package->discount_per) / 100 : 0;
        $finalPrice = $package->price - $discountAmount;

        PackageTransaction::create([
            'user_id' => $user->id,
            'package1_id' => $package->id,
            'ulid' => $user->ulid,
            'package_name' => $package->package_name,
            'price' => $package->price,
            'discount_percentage' => $package->discount_per,
            'discount_amount' => $discountAmount,
            'final_price' => $finalPrice,
        ]);

        $user->update([
            'status' => 'active',
            'user_doa' => now()
        ]);

        return redirect()->route('admin.packages.assign')
            ->with('success', 'Package assigned successfully!');
    }



    // For the User's Dashboard
    
     public function viewUserPackage()
    {
        $userId = Auth::id();
       
        // Get all package transactions for this user with package details
        $packages = Package2Purchase::with(['package2','rateDetail'])
            ->where('user_id', $userId)
            ->latest()
            ->get();
            
        return view('user.packages', compact('packages'));
    }

    public function viewActivationPackage()
    {
        $userId = Auth::id();
        $package = PackageTransaction::where('user_id', $userId)
            ->latest()
            ->first();
            // dd($packageTransaction);
            return view('user.activation-package', compact('package'));
    }
}
