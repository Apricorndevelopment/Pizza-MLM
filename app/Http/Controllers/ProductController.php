<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    // Display all products
    public function index()
    {
        // Custom sorting: Pending (1) first, then Approved/Rejected (2), then by Date
        $products = Product::orderByRaw("FIELD(status, 'pending', 'approved', 'rejected')")
            ->latest()
            ->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    // Show create product form
    public function create()
    {
        return view('admin.products.create');
    }

    // Store new product
    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'product_image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'price' => 'required|numeric|min:0',
            'mrp' => 'required|numeric|min:0',
            'gst' => 'required|numeric|min:0',
            'dp' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'pv' => 'nullable|numeric|min:0',
            'percentage' => 'nullable|numeric|min:0',
            'max_coupon_usage' => 'nullable|integer|min:0'
        ]);

        $data = $request->all();
        $data['status'] = 'approved';
        Product::create($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    // Show edit form
    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    // Update product
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'profit' => 'required|numeric|min:0',
            'mrp' => 'required|numeric|min:0',
            'gst' => 'required|numeric|min:0',
            'dp' => 'required|numeric|min:0',
            'status' => 'required|in:pending,approved,rejected',
            'description' => 'nullable|string',
            'pv' => 'nullable|numeric|min:0',
            'max_coupon_usage' => 'nullable|integer|min:0',
            'percentage' => 'nullable|numeric|min:0',
            'isVeg' => 'required|string|in:veg,non-veg',
        ]);

        $product->fill($request->except('product_image'));

        // Image Update Logic (Admin ke liye bhi same)
        if ($request->hasFile('product_image')) {
            if ($product->product_image && File::exists(public_path($product->product_image))) {
                File::delete(public_path($product->product_image));
            }
            $file = $request->file('product_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('products'), $filename);
            $product->product_image = 'products/' . $filename;
        }

        $product->save();
        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully');
    }

    // Delete product
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully');
    }
}
