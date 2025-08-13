<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'uphone' => 'required',
            'message' => 'required|string',
        ]);

        Mail::to('developerapricorn1234@gmail.com')->send(new ContactMail($data));

        return back()->with('success', 'Your message has been sent successfully!');
    }




    //User Network Related Pages 

    public function networkSummary()
    {
        $authUser = Auth::user();

        // Get all downline users (flat array)
        $downlineUsers = $this->getDownlineUsers($authUser->ulid);

        // Add level to each user
        foreach ($downlineUsers as $user) {
            $user->level = $this->calculateLevel($authUser->ulid, $user->ulid);
        }

        return view('user.network.summary', compact('downlineUsers'));
    }

    // Recursively get all users below a given ULID.
    private function getDownlineUsers($ulid, &$results = [])
    {
        $users = User::where('sponsor_id', $ulid)->get();

        foreach ($users as $user) {
            $results[] = $user; // Add current user
            $this->getDownlineUsers($user->ulid, $results); // Add children
        }

        return $results;
    }

    // Calculate the level of a target user relative to a starting user, using ULID. 
    private function calculateLevel($startUlid, $targetUlid, $level = 0)
    {
        if ($startUlid === $targetUlid) {
            return $level;
        }

        $targetUser = User::where('ulid', $targetUlid)->first();

        if (!$targetUser || !$targetUser->sponsor_id) {
            return null;
        }

        return $this->calculateLevel($startUlid, $targetUser->sponsor_id, $level + 1);
    }


    public function directTeam()
    {
        $authUser = Auth::user();

        // Direct team = users whose sponsor_id is current user's ULID
        $directTeam = User::where('sponsor_id', $authUser->ulid)->get();

        return view('user.network.direct-team', compact('directTeam'));
    }
}
