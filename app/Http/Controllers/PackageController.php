<?php

namespace App\Http\Controllers;

use App\Models\Package1;
use App\Models\ProductPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PackageController extends Controller
{
    public function package()
    {
        $package1 = Package1::all();
        return view('admin.manage-package.package', compact('package1'));
    }

    public function productPackage()
    {
        $product_package = ProductPackage::paginate(10);
        return view('admin.manage-package.product-package', compact('product_package'));
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
        try {
            // 1. Merge defaults to ensure logic holds if JS fails
            $request->merge([
                'capping' => $request->capping ?? 0,
            ]);

            // 2. Validate
            $validated = $request->validate([
                'product_name'       => 'required|string|max:255',
                'product_image'      => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
                'description'        => 'nullable|string',
                'mrp'                => 'required|numeric|min:0',
                'gst'                => 'nullable|numeric|min:0|max:100',
                'dp'                 => 'required|numeric|min:0',
                'pv'                 => 'required|numeric|min:0',
                'max_coupon_usage'   => 'required|integer|min:0',
                'capping'            => 'required|numeric|min:0',
                'profit'             => 'required|numeric|min:0',

                // FIX: Use 'in:0,1' instead of 'boolean' to accept the string "1" from your dump
                'is_package_product' => 'required|in:0,1',
                'isVeg'              => 'required|in:veg,non-veg',
            ]);

            DB::beginTransaction();

            // 3. Handle Image Upload
            $imagePath = null;
            if ($request->hasFile('product_image')) {
                $file = $request->file('product_image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('products'), $filename);
                $imagePath = 'products/' . $filename;
            }

            // 4. Create Product
            ProductPackage::create([
                'product_name'       => $validated['product_name'],
                'product_image'      => $imagePath,
                'description'        => $validated['description'] ?? null,
                'mrp'                => $validated['mrp'],
                'gst'                => $validated['gst'],
                'dp'                 => $validated['dp'],
                'pv'                 => $validated['pv'],
                'profit'             => $validated['profit'],
                'max_coupon_usage'   => $validated['max_coupon_usage'],

                // Cast to integer to match tinyint(1) database column
                'is_package_product' => (int) $validated['is_package_product'],
                'capping'            => $validated['capping'],
                'isVeg'              => $validated['isVeg'],
            ]);

            DB::commit();

            return redirect()->route('admin.product-package')
                ->with('success', 'Product Package created successfully!');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Product Package Store Error', [
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Something went wrong: ' . $e->getMessage());
        }
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
            'gst' => 'nullable|numeric|min:0|max:100',
            'dp' => 'required|numeric|min:0',
            'pv' => 'required|numeric|min:0',
            'max_coupon_usage' => 'required|integer|min:0',
            'profit' => 'required|numeric|min:0|max:100',
            'isVeg' => 'required|string|in:veg,non-veg',
            'capping'            => 'required|numeric|min:0',
            'is_package_product' => 'required|in:0,1',
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
            'profit' => $request->profit,
            'max_coupon_usage' => $request->max_coupon_usage,
            'isVeg' => $request->isVeg,
            // Cast to integer to match tinyint(1) database column
            'is_package_product' => (int) $request->is_package_product,
            'capping'            => $request->capping,
        ]);

        return redirect()->route('admin.product-package')->with('success', 'Product updated successfully!');
    }

    public function destroyProductPackage($id)
    {
        $package = ProductPackage::findOrFail($id);

        if ($package->product_image && File::exists(public_path($package->product_image))) {
            File::delete(public_path($package->product_image));
        }

        $package->delete();

        return redirect()->route('admin.product-package')->with('success', 'Product Package deleted successfully');
    }

    public function updateStock(Request $request, $id)
    {
        $product = ProductPackage::findOrFail($id);

        $request->validate([
            'manage_stock' => 'required|boolean',
            'stock_quantity' => 'required_if:manage_stock,true|integer|min:0',
        ]);

        // Update stock logic
        $product->update([
            'manage_stock' => $request->manage_stock,
            // Agar stock manage karna hai to quantity update karo, varna 0 rakho
            'stock_quantity' => $request->manage_stock ? $request->stock_quantity : 0,
        ]);

        return back()->with('success', 'Stock updated successfully!');
    }
}
