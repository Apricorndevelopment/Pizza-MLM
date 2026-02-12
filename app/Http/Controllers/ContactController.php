<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;
use App\Models\ProductPackagePurchase;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        Mail::to('geokranti@gmail.com')->send(new ContactMail($data));

        return back()->with('success', 'Your message has been sent successfully!');
    }




    //User Network Related Pages 

    public function networkSummary(Request $request)
    {
        $breadcrumbs = [
            ['title' => 'Network', 'url' => route('user.network.summary')],
            ['title' => 'Network Summary', 'url' => route('user.network.summary')]
        ];

        $authUser = Auth::user();

        // 1. Get flat list of users with levels calculated during recursion
        // Passing 1 as the starting level relative to the current user
        $downlineUsers = $this->getDownlineUsers($authUser->ulid, 1);

        // 2. Enrich data (Purchases & Status)
        foreach ($downlineUsers as $key => $user) {
            // Check if user has purchases
            // Optimization: You could eager load this in the recursive function if using relationships, 
            // // but for raw SQL/recursion, this is acceptable for now.
            // $totalPurchase = ProductPackagePurchase::where('user_id', $user->id)->sum('final_price');

            // $user->purchase_status = $totalPurchase > 0 ? 'paid' : 'unpaid';
            // $user->total_purchases = $totalPurchase;
        }

        // NEW: Get all available levels for the filter dropdown (before filtering)
        $levels = collect($downlineUsers)->pluck('level')->unique()->sort()->values()->toArray();

        // 3. Apply Filters
        // Added 'level' to the check list
        if ($request->hasAny(['designation', 'status', 'purchase_status', 'start_date', 'end_date', 'level'])) {
            $downlineUsers = $this->applyFilters($downlineUsers, $request);
        }

        // 4. SORTING BY LEVEL (Level 1 -> Level 2 -> Level 3)
        // We convert array to collection to use sortBy easily
        $downlineCollection = collect($downlineUsers)->sortBy([
            ['level', 'asc'],
            ['created_at', 'desc'],
        ]);

        // 5. Pagination
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 15;
        $currentItems = $downlineCollection->slice(($currentPage - 1) * $perPage, $perPage)->all();

        $paginatedUsers = new LengthAwarePaginator($currentItems, $downlineCollection->count(), $perPage, $currentPage, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
            'query' => $request->query(), // Important: Persists search filters across pages
        ]);

        // Get designations for filter
        $designations = DB::table('percentage_rewards')->pluck('rank')->toArray();

        return view('user.network.summary', compact('paginatedUsers', 'designations', 'breadcrumbs','levels'));
    }

    // Apply filters to user collection
    private function applyFilters($users, $request)
    {
        // Filter by designation
        if ($request->filled('designation')) {
            $users = array_filter($users, function ($user) use ($request) {
                return $user->current_rank == $request->designation;
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $users = array_filter($users, function ($user) use ($request) {
                return $user->status == $request->status;
            });
        }

        // Filter by purchase status
        if ($request->filled('purchase_status')) {
            $users = array_filter($users, function ($user) use ($request) {
                return $user->purchase_status == $request->purchase_status;
            });
        }

        // NEW: Filter by Level
        if ($request->filled('level')) {
            $users = array_filter($users, function ($user) use ($request) {
                return $user->level == $request->level;
            });
        }

        // Filter by date range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $users = array_filter($users, function ($user) use ($request) {
                $userDate = $user->user_doa ?? $user->created_at;
                return $userDate >= $request->start_date && $userDate <= $request->end_date;
            });
        }

        return array_values($users);
    }

    // Recursively get all users below a given ULID.
    // Optimized Recursive Function
    private function getDownlineUsers($sponsorId, $currentLevel, &$results = [])
    {
        // Fetch direct children
        $children = User::where('sponsor_id', $sponsorId)->get();

        foreach ($children as $child) {
            // Assign the calculated level immediately
            $child->level = $currentLevel;
            $results[] = $child;

            // Recurse for the next level
            $this->getDownlineUsers($child->ulid, $currentLevel + 1, $results);
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

        $breadcrumbs = [
            ['title' => 'Network', 'url' => route('user.view.userTree')],
            ['title' => 'Direct Team', 'url' => route('user.direct.team')]
        ];

        return view('user.network.direct-team', compact('directTeam', 'breadcrumbs'));
    }
}
