<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SliderUser;
use Illuminate\Support\Facades\File;

class SliderUserController extends Controller
{
    public function index()
    {
        $users = SliderUser::latest()->get();
        return view('admin.slider-users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'rank' => 'required|string|max:255',
            'photo' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $photoPath = null;

        // Image Upload Logic
        if ($request->hasFile('photo')) {
            $directory = 'storage/slider-users';
            
            // Create directory if not exists
            if (!File::exists(public_path($directory))) {
                File::makeDirectory(public_path($directory), 0755, true);
            }

            $file = $request->file('photo');
            $extension = $file->getClientOriginalExtension();
            $filename = 'user_' . uniqid() . '.' . $extension;

            // Move file
            $file->move(public_path($directory), $filename);
            
            // Save relative path
            $photoPath = $directory . '/' . $filename;
        }

        SliderUser::create([
            'name' => $request->name,
            'rank' => $request->rank,
            'photo' => $photoPath,
        ]);

        return redirect()->back()->with('success', 'Slider user added successfully!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'rank' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $sliderUser = SliderUser::findOrFail($id);

        if ($request->hasFile('photo')) {
            // Delete old photo if it exists
            if ($sliderUser->photo) {
                $oldPath = public_path($sliderUser->photo);
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }

            // Create directory if not exists
            $directory = 'storage/slider-users';
            if (!File::exists(public_path($directory))) {
                File::makeDirectory(public_path($directory), 0755, true);
            }

            // Upload new file
            $file = $request->file('photo');
            $extension = $file->getClientOriginalExtension();
            $filename = 'user_' . uniqid() . '.' . $extension;

            $file->move(public_path($directory), $filename);

            // Update path
            $sliderUser->photo = $directory . '/' . $filename;
        }

        $sliderUser->name = $request->name;
        $sliderUser->rank = $request->rank;
        $sliderUser->save();

        return redirect()->back()->with('success', 'User updated successfully!');
    }

    public function destroy($id)
    {
        $sliderUser = SliderUser::findOrFail($id);

        // Delete photo from storage
        if ($sliderUser->photo) {
            $oldPath = public_path($sliderUser->photo);
            if (File::exists($oldPath)) {
                File::delete($oldPath);
            }
        }

        $sliderUser->delete();

        return redirect()->back()->with('success', 'User deleted successfully!');
    }
}