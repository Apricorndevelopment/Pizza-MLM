<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Complaint;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class ComplaintController extends Controller
{
    public function index()
    {
        $complaints = Complaint::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('user.complaints.index', compact('complaints'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|in:Payment Issue,Product Quality,Technical Support,Account Issue,Other',
            'message' => 'required|string|max:1000',
            'image'   => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            // 'public_path' points directly to 'root/public' folder
            $directory = public_path('storage/complaints');

            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }

            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = uniqid() . '.' . $extension;

            $file->move($directory, $filename);

            $imagePath = 'storage/complaints/' . $filename;
        }

        Complaint::create([
            'user_id'   => Auth::id(),
            'is_vendor' => Auth::user()->is_vendor, // <--- Added this line
            'subject'   => $request->subject,
            'message'   => $request->message,
            'image'     => $imagePath,
            'status'    => 'pending'
        ]);

        return back()->with('success', 'Complaint ticket created successfully.');
    }
}
