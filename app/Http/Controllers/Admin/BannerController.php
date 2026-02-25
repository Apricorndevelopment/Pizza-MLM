<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VendorBanner;
use App\Models\ProductBanner;
use App\Models\UserGallery;
use Illuminate\Support\Facades\File;

class BannerController extends Controller
{
    // ==========================================
    // VENDOR BANNERS LOGIC
    // ==========================================

    public function vendorIndex()
    {
        $banners = VendorBanner::latest()->get();
        return view('admin.banners.vendor-banners', compact('banners'));
    }

    public function vendorStore(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'banner_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'link' => 'nullable|url'
        ]);

        $imagePath = $this->uploadImage($request, 'banner_image', 'storage/vendor-banners');

        VendorBanner::create([
            'title' => $request->title,
            'banner_image' => $imagePath,
            'link' => $request->link
        ]);

        return back()->with('success', 'Vendor Banner added successfully!');
    }

    public function vendorUpdate(Request $request, $id)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'link' => 'nullable|url'
        ]);

        $banner = VendorBanner::findOrFail($id);

        if ($request->hasFile('banner_image')) {
            $this->deleteImage($banner->banner_image);
            $banner->banner_image = $this->uploadImage($request, 'banner_image', 'storage/vendor-banners');
        }

        $banner->update([
            'title' => $request->title,
            'link' => $request->link
        ]);

        return back()->with('success', 'Vendor Banner updated successfully!');
    }

    public function vendorDestroy($id)
    {
        $banner = VendorBanner::findOrFail($id);
        $this->deleteImage($banner->banner_image);
        $banner->delete();
        return back()->with('success', 'Vendor Banner deleted successfully!');
    }

    // ==========================================
    // PRODUCT BANNERS LOGIC
    // ==========================================

    public function productIndex()
    {
        $banners = ProductBanner::latest()->get();
        return view('admin.banners.product-banners', compact('banners'));
    }

    public function productStore(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'banner_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'link' => 'nullable|url'
        ]);

        $imagePath = $this->uploadImage($request, 'banner_image', 'storage/product-banners');

        ProductBanner::create([
            'title' => $request->title,
            'banner_image' => $imagePath,
            'link' => $request->link
        ]);

        return back()->with('success', 'Product Banner added successfully!');
    }

    public function productUpdate(Request $request, $id)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'link' => 'nullable|url'
        ]);

        $banner = ProductBanner::findOrFail($id);

        if ($request->hasFile('banner_image')) {
            $this->deleteImage($banner->banner_image);
            $banner->banner_image = $this->uploadImage($request, 'banner_image', 'storage/product-banners');
        }

        $banner->update([
            'title' => $request->title,
            'link' => $request->link
        ]);

        return back()->with('success', 'Product Banner updated successfully!');
    }

    public function productDestroy($id)
    {
        $banner = ProductBanner::findOrFail($id);
        $this->deleteImage($banner->banner_image);
        $banner->delete();
        return back()->with('success', 'Product Banner deleted successfully!');
    }

    // ==========================================
    // HELPER FUNCTIONS
    // ==========================================

    private function uploadImage($request, $inputName, $directory)
    {
        if ($request->hasFile($inputName)) {
            if (!File::exists(public_path($directory))) {
                File::makeDirectory(public_path($directory), 0755, true);
            }

            $file = $request->file($inputName);
            $extension = $file->getClientOriginalExtension();
            $filename = 'banner_' . uniqid() . '.' . $extension;
            $file->move(public_path($directory), $filename);

            return $directory . '/' . $filename;
        }
        return null;
    }

    private function deleteImage($path)
    {
        if ($path && File::exists(public_path($path))) {
            File::delete(public_path($path));
        }
    }

    public function userGalleryIndex()
    {
        $galleries = UserGallery::latest()->get();
        return view('admin.banners.user-gallery', compact('galleries'));
    }

    public function userGalleryStore(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'photo' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        // Using the existing uploadImage helper
        $imagePath = $this->uploadImage($request, 'photo', 'storage/user-gallery');

        UserGallery::create([
            'title' => $request->title,
            'photo' => $imagePath
        ]);

        return back()->with('success', 'Gallery Image added successfully!');
    }

    public function userGalleryUpdate(Request $request, $id)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $gallery = UserGallery::findOrFail($id);

        if ($request->hasFile('photo')) {
            // Using existing deleteImage helper
            $this->deleteImage($gallery->photo);
            $gallery->photo = $this->uploadImage($request, 'photo', 'storage/user-gallery');
        }

        $gallery->update([
            'title' => $request->title,
        ]);

        return back()->with('success', 'Gallery Image updated successfully!');
    }

    public function userGalleryDestroy($id)
    {
        $gallery = UserGallery::findOrFail($id);
        $this->deleteImage($gallery->photo);
        $gallery->delete();
        return back()->with('success', 'Gallery Image deleted successfully!');
    }
}
