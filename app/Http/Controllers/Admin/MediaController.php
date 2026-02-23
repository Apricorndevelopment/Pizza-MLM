<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MediaLibrary;
use Illuminate\Support\Facades\File;

class MediaController extends Controller
{
    public function index()
    {
        // Audio aur Video alag-alag fetch kar rahe hain tabs ke liye
        $audios = MediaLibrary::where('type', 'audio')->latest()->get();
        $videos = MediaLibrary::where('type', 'video')->latest()->get();
        
        return view('admin.media.index', compact('audios', 'videos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type'  => 'required|in:audio,video',
            // Validation: Audio ke liye mp3, wav | Video ke liye mp4, webm
            'file'  => 'required|file|mimes:mp3,wav,ogg,mp4,webm,avi,mkv|max:256000', // Max 50MB
        ],[
            'file.mimes' => 'Invalid file type. Supported: mp3, wav, ogg for audio and mp4, webm, avi, mkv for videos.',
            'file.max' => 'File size should be less than 250MB.',
        ]);

        if ($request->hasFile('file')) {
            $type = $request->type;
            $directory = "storage/media/{$type}"; // storage/media/audio ya storage/media/video

            if (!File::exists(public_path($directory))) {
                File::makeDirectory(public_path($directory), 0755, true);
            }

            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $filename = $type . '_' . uniqid() . '.' . $extension;

            $file->move(public_path($directory), $filename);
            
            MediaLibrary::create([
                'title' => $request->title,
                'type' => $type,
                'file_path' => $directory . '/' . $filename
            ]);

            return back()->with('success', ucfirst($type) . ' uploaded successfully!');
        }

        return back()->with('error', 'File upload failed.');
    }

    public function destroy($id)
    {
        $media = MediaLibrary::findOrFail($id);

        // File delete karein
        if (File::exists(public_path($media->file_path))) {
            File::delete(public_path($media->file_path));
        }

        $media->delete();

        return back()->with('success', 'Media deleted successfully!');
    }
}