<?php

namespace App\Http\Controllers;

use App\Models\Package1;
use App\Models\Package2;
use App\Models\Package2Details;
use Illuminate\Http\Request;


class PackageController extends Controller
{
    public function package()
    {
        $package1 = Package1::all();
        $package2 = Package2::all();
        return view('admin.package', compact('package1', 'package2'));
    }

    public function createPackage1()
    {
        return view('admin.package1-create');
    }

    public function createPackage2()
    {
        return view('admin.package2-create');
    }

    public function storepackage1(Request $request)
    {
        $request->validate([
            'package_name' => 'required|string|max:255',
            'package_quantity' => 'required|string',
            'price' => 'required|numeric',
            'description' => 'nullable',
            'discount' => 'nullable|numeric|min:0|max:100'
        ]);

        $discountAmount = null;
        if ($request->filled('discount')) {
            $discountAmount = ($request->price * $request->discount) / 100;
        }

        Package1::create([
            'package_name' => $request->package_name,
            'package_quantity' => $request->package_quantity,
            'price' => $request->price,
            'description' => $request->description,
            'discount' => $discountAmount,
            'discount_per' => $request->discount
        ]);

        return redirect()->route('admin.package')->with('success', 'Package added successfully');
    }
    public function storepackage2(Request $request)
    {
        $request->validate([
            'package_name' => 'required|string|max:255',
            'package_quantity' => 'required|string',
            'price' => 'required|string',
            'description' => 'nullable|string',
            'maturity' => 'required|string',
            'rates' => 'required|array',
            'rates.*' => 'required|numeric',
            'times' => 'array',
            'times.*' => '',
            'capitals' => 'array',
            'capitals.*' => '',
            'profit_shares' => 'array',
            'profit_shares.*' => '',
        ]);

        $package = Package2::create([
            'package_name' => $request->package_name,
            'package_quantity' => $request->package_quantity,
            'price' => $request->price,
            'description' => $request->description,
            'maturity' => $request->maturity,
        ]);

        foreach ($request->rates as $key => $rate) {
            Package2Details::create([
                'package2_id' => $package->id,
                'rate' => $rate,
                'time' => $request->times[$key],
                'capital' => $request->capitals[$key],
                'profit_share' => $request->profit_shares[$key],
            ]);
        }

        return redirect()->route('admin.package')->with('success', 'Package with rates added successfully');
    }


    public function editPackage1($id)
    {
        $package = Package1::findOrFail($id);
        return view('admin.package1-edit', compact('package'));
    }

    public function updatePackage1(Request $request, $id)
    {
        $request->validate([
            'package_name' => 'required|string|max:255',
            'package_quantity' => 'required|string',
            'price' => 'required|numeric',
            'description' => 'nullable',
            'discount' => 'nullable|numeric|min:0|max:100'
        ]);

        $discountAmount = null;
        if ($request->filled('discount')) {
            $discountAmount = ($request->price * $request->discount) / 100;
        }

        $package = Package1::findOrFail($id);
        $package->update([
            'package_name' => $request->package_name,
            'package_quantity' => $request->package_quantity,
            'price' => $request->price,
            'description' => $request->description,
            'discount' => $discountAmount,
            'discount_per' => $request->discount
        ]);

        return redirect()->route('admin.package')->with('success', 'Package 1 updated successfully');
    }

    public function destroyPackage1($id)
    {
        $package = Package1::findOrFail($id);
        $package->delete();

        return redirect()->route('admin.package')->with('success', 'Package 1 deleted successfully');
    }


    public function editPackage2($id)
    {
        $package = Package2::with('details')->findOrFail($id);
        return view('admin.package2-edit', compact('package'));
    }


    public function updatePackage2(Request $request, $id)
    {
        $request->validate([
            'package_name' => 'required|string|max:255',
            'package_quantity' => 'required|string',
            'price' => 'required|string',
            'description' => 'nullable|string',
            'maturity' => 'required|string',
            'rates' => 'required|array|min:1',
            'rates.*' => 'required|numeric',
            'times' => 'required|array',
            'capitals' => 'required|array',
            'profit_shares' => 'required|array',
        ]);

        $package = Package2::findOrFail($id);
        $package->update([
            'package_name' => $request->package_name,
            'package_quantity' => $request->package_quantity,
            'price' => $request->price,
            'description' => $request->description,
            'maturity' => $request->maturity,
        ]);

        // Delete existing rate details
        $package->details()->delete();

        // Insert updated rate details
        foreach ($request->rates as $key => $rate) {
            Package2Details::create([
                'package2_id' => $package->id,
                'rate' => $rate,
                'time' => $request->times[$key] ?? null,
                'capital' => $request->capitals[$key] ?? null,
                'profit_share' => $request->profit_shares[$key] ?? null,
            ]);
        }

        return redirect()->route('admin.package')->with('success', 'Package updated successfully with new rate details!');
    }

    public function destroyPackage2($id)
    {
        $package = Package2::findOrFail($id);
        $package->delete();

        return redirect()->route('admin.package')->with('success', 'Package 2 deleted successfully');
    }


}
