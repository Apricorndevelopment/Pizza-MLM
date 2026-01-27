<?php

namespace App\Http\Controllers;

use App\Models\Package1;
use App\Models\ProductPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PackageController extends Controller
{
    public function package()
    {
        $package1 = Package1::all();
        $product_package = ProductPackage::all();
        return view('admin.manage-package.package', compact('package1', 'product_package'));
    }

    public function createPackage1()
    {
        return view('admin.manage-package.package1-create');
    }

    public function createProductPackage()
    {
        return view('admin.manage-package.package2-create');
    }

    public function storePackage1(Request $request)
    {
        $request->validate([
            'package_name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'nullable',
        ]);

        Package1::create([
            'package_name' => $request->package_name,
            'price' => $request->price,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.package')->with('success', 'Package added successfully');
    }

    public function storeProductPackage(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'description' => 'nullable|string',
            'mrp' => 'required|numeric|min:0',
            'gst' => 'required|numeric|min:0|max:100',
            'dp' => 'required|numeric|min:0',
            'pv' => 'required|numeric|min:0',
            'max_coupon_usage' => 'required|integer|min:1',
            'percentage' => 'required|numeric|min:0|max:100',
        ]);

        $imagePath = null;
        if ($request->hasFile('product_image')) {
            $file = $request->file('product_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            
            // Move file directly to public/products
            $file->move(public_path('products'), $filename);
            
            // Store relative path
            $imagePath = 'products/' . $filename;
        }

        ProductPackage::create([
            'product_name' => $request->product_name,
            'product_image' => $imagePath,
            'description' => $request->description,
            'mrp' => $request->mrp,
            'gst' => $request->gst,
            'dp' => $request->dp,
            'pv' => $request->pv,
            'max_coupon_usage' => $request->max_coupon_usage,
            'percentage' => $request->percentage,
        ]);

        return redirect()->route('admin.package')->with('success', 'Product Package created successfully!');
    }

    public function editPackage1($id)
    {
        $package = Package1::findOrFail($id);
        return view('admin.manage-package.package1-edit', compact('package'));
    }

    public function updatePackage1(Request $request, $id)
    {
        $request->validate([
            'package_name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'nullable',
        ]);

        $package = Package1::findOrFail($id);
        $package->update([
            'package_name' => $request->package_name,
            'price' => $request->price,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.package')->with('success', 'Package 1 updated successfully');
    }

    public function destroyPackage1($id)
    {
        $package = Package1::findOrFail($id);
        $package->delete();

        return redirect()->route('admin.package')->with('success', 'Package 1 deleted successfully');
    }

    public function editProductPackage($id)
    {
        $product = ProductPackage::findOrFail($id);
        return view('admin.manage-package.package2-edit', compact('product'));
    }

    public function updateProductPackage(Request $request, $id)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'description' => 'nullable|string',
            'mrp' => 'required|numeric|min:0',
            'gst' => 'required|numeric|min:0|max:100',
            'dp' => 'required|numeric|min:0',
            'pv' => 'required|numeric|min:0',
            'max_coupon_usage' => 'required|integer|min:1',
            'percentage' => 'required|numeric|min:0|max:100',
        ]);

        $product = ProductPackage::findOrFail($id);
        $imagePath = $product->product_image;

        if ($request->hasFile('product_image')) {
            // Delete old image if it exists
            if ($product->product_image && File::exists(public_path($product->product_image))) {
                File::delete(public_path($product->product_image));
            }
            
            $file = $request->file('product_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            
            // Move file directly to public/products
            $file->move(public_path('products'), $filename);
            
            $imagePath = 'products/' . $filename;
        }

        $product->update([
            'product_name' => $request->product_name,
            'product_image' => $imagePath,
            'description' => $request->description,
            'mrp' => $request->mrp,
            'gst' => $request->gst,
            'dp' => $request->dp,
            'pv' => $request->pv,
            'percentage' => $request->percentage,
            'max_coupon_usage' => $request->max_coupon_usage
        ]);

        return redirect()->route('admin.package')->with('success', 'Product updated successfully!');
    }

    public function destroyProductPackage($id)
    {
        $package = ProductPackage::findOrFail($id);

        if ($package->product_image && File::exists(public_path($package->product_image))) {
            File::delete(public_path($package->product_image));
        }

        $package->delete();

        return redirect()->route('admin.package')->with('success', 'Product Package deleted successfully');
    }
}