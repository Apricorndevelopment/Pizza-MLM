<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SingleLegPlanController extends Controller
{
    // 1. Load Initial Single Leg View
    public function singleLegTree()
    {
        $user = Auth::user(); // Logged-in user

        // Single leg में चूँकि हर नया यूज़र सबसे आखिर में जुड़ता है,
        // इसलिए इस यूज़र के बाद रजिस्टर हुए सभी यूज़र्स (ID > Current ID) इसकी डाउनलाइन हैं।
        $totalTeam = User::where('id', '>', $user->id)
            ->whereNotNull('parent_id')
            ->count();
        $ActiveTeam = User::where('id', '>', $user->id)
            ->whereNotNull('parent_id')->where('status', 'active')
            ->count();
        $InactiveTeam = User::where('id', '>', $user->id)
            ->whereNotNull('parent_id')->where('status', 'inactive')
            ->count();

        return view('user.network.single_leg', compact('user', 'totalTeam', 'ActiveTeam', 'InactiveTeam'));
    }

    // 2. AJAX Method to fetch the immediate next user in the Single Leg
    public function fetchNextSingleLegNode($ulid)
    {
        // Find the user whose parent_id matches the given ULID
        $child = User::where('parent_id', $ulid)->first();

        if (!$child) {
            return response()->json(['success' => false, 'message' => 'No further team members.']);
        }

        // Check if this child also has someone below them
        $hasNext = User::where('parent_id', $child->ulid)->exists();

        return response()->json([
            'success' => true,
            'user' => [
                'name' => $child->name,
                'ulid' => $child->ulid,
                'status' => $child->status,
            ],
            'has_next' => $hasNext
        ]);
    }


    //Admin Side
    // 1. Load Admin Single Leg View (Starts from Top User)
    public function singleLegTreeAdmin()
    {
        // सिस्टम का सबसे पहला यूज़र (Root User) निकालें
        $user = \App\Models\User::orderBy('id', 'asc')->first();

        $totalTeam = 0;
        $ActiveTeam = 0;
        $InactiveTeam = 0;
        if ($user) {
            // रूट यूज़र के नीचे वाले सभी सिंगल लेग यूज़र्स
            $totalTeam = User::where('id', '>', $user->id)
                                         ->whereNotNull('parent_id')
                                         ->count();
            $ActiveTeam = User::where('id', '>', $user->id)
                                         ->whereNotNull('parent_id')->where('status', 'active') 
                                         ->count();
            $InactiveTeam = User::where('id', '>', $user->id)
                                         ->whereNotNull('parent_id')->where('status', 'inactive')
                                         ->count();
        } else {
            return back()->with('error', 'No users found in the system yet.');
        }

        return view('admin.single_leg_tree', compact('user', 'totalTeam', 'ActiveTeam', 'InactiveTeam'));
    }

    // 2. Admin AJAX Method to fetch the next user
    public function fetchNextSingleLegNodeAdmin($ulid)
    {
        $child = User::where('parent_id', $ulid)->first();

        if (!$child) {
            return response()->json(['success' => false, 'message' => 'No further team members.']);
        }

        $hasNext = User::where('parent_id', $child->ulid)->exists();

        return response()->json([
            'success' => true,
            'user' => [
                'name' => $child->name,
                'ulid' => $child->ulid,
                'status' => $child->status,
            ],
            'has_next' => $hasNext
        ]);
    }
}
