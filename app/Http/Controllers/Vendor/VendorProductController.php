<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class VendorProductController extends Controller
{
    // Vendor ke products ki list
    public function index()
    {
        $user = Auth::user();
        // Vendor table se ID nikalo
        $vendor = Vendor::where('user_id', $user->id)->firstOrFail();

        $products = Product::where('vendor_id', $vendor->id)->latest()->paginate(10);
        return view('vendors.products.index', compact('products'));
    }

    // Product Add Form
    public function create()
    {
        return view('vendors.products.create');
    }

    // Store Product (Vendor Side)
    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'product_image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'profit' => 'required|numeric|min:0',
            'mrp' => 'required|numeric|min:0',
            'gst' => 'required|numeric|min:0',
            'dp' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'isVeg' => 'required|string|in:veg,non-veg',
        ]);

        $user = Auth::user();
        $vendor = Vendor::where('user_id', $user->id)->firstOrFail();

        $product = new Product();
        $product->vendor_id = $vendor->id;
        $product->vendor_user_id = $user->id;
        $product->product_name = $request->product_name;
        $product->profit = $request->profit;
        $product->mrp = $request->mrp;
        $product->gst = $request->gst;
        $product->dp = $request->dp;
        $product->description = $request->description;
        $product->status = 'pending'; // Default Pending
        $product->isVeg =  $request->isVeg;

        // Image Upload Logic (Provided by you)
        if ($request->hasFile('product_image')) {
            $file = $request->file('product_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('products'), $filename);
            $product->product_image = 'products/' . $filename;
        }

        $product->save();

        return redirect()->route('vendor.products.index')->with('success', 'Product uploaded successfully! Pending Admin Approval.');
    }

    public function show($id)
    {
        $product = Product::where('id', $id)->where('vendor_user_id', Auth::id())->firstOrFail();
        return view('vendors.products.show', compact('product'));
    }

    // Edit Form
    public function edit($id)
    {
        $product = Product::where('id', $id)->where('vendor_user_id', Auth::id())->firstOrFail();
        return view('vendors.products.edit', compact('product'));
    }

    // Update Product
    public function update(Request $request, $id)
    {
        $product = Product::where('id', $id)->where('vendor_user_id', Auth::id())->firstOrFail();

        $request->validate([
            'product_name' => 'required|string|max:255',
            'profit' => 'required|numeric',
            'mrp' => 'required|numeric',
            'gst' => 'required|numeric',
            'dp' => 'required|numeric',
            'product_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'description' => 'nullable|string',
            'isVeg' => 'required|string|in:veg,non-veg',
        ]);

        $product->product_name = $request->product_name;
        $product->profit = $request->profit;
        $product->mrp = $request->mrp;
        $product->gst = $request->gst;
        $product->dp = $request->dp;
        $product->description = $request->description;
        $product->isVeg =  $request->isVeg;

        // Agar edit kiya to wapas pending kar sakte hain logic ke hisaab se
        $product->status = 'pending';

        if ($request->hasFile('product_image')) {
            // Delete old image
            if ($product->product_image && File::exists(public_path($product->product_image))) {
                File::delete(public_path($product->product_image));
            }

            $file = $request->file('product_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('products'), $filename);
            $product->product_image = 'products/' . $filename;
        }

        $product->save();

        return redirect()->route('vendor.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy($id)
    {
        $product = Product::where('id', $id)->where('vendor_user_id', Auth::id())->firstOrFail();

        if ($product->product_image && File::exists(public_path($product->product_image))) {
            File::delete(public_path($product->product_image));
        }

        $product->delete();
        return redirect()->route('vendor.products.index')->with('success', 'Product deleted successfully.');
    }


    public function updateStock(Request $request, $id)
    {
        $product = Product::where('id', $id)->where('vendor_user_id', Auth::id())->firstOrFail();

        $request->validate([
            'manage_stock' => 'required|boolean',
            'stock_quantity' => 'required_if:manage_stock,true|integer|min:0',
        ]);

        $product->update([
            'manage_stock' => $request->manage_stock,
            'stock_quantity' => $request->manage_stock ? $request->stock_quantity : 0,
        ]);

        return back()->with('success', 'Stock updated successfully!');
    }
}
