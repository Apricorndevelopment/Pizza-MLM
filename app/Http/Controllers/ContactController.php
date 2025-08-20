<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;
use App\Models\Package2Purchase;
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

        Mail::to('developerapricorn1234@gmail.com')->send(new ContactMail($data));

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

        // Get all downline users (flat array)
        $downlineUsers = $this->getDownlineUsers($authUser->ulid);

        // Add level and purchase status to each user
        foreach ($downlineUsers as $user) {
            $user->level = $this->calculateLevel($authUser->ulid, $user->ulid);

            // Check if user has purchases (paid/unpaid status)
            $hasPurchases = Package2Purchase::where('user_id', $user->id)
                ->exists();
            $user->purchase_status = $hasPurchases ? 'paid' : 'unpaid';

            // Calculate total purchases if user has any
            if ($hasPurchases) {
                $user->total_purchases = Package2Purchase::where('user_id', $user->id)
                    ->sum('final_price');
            } else {
                $user->total_purchases = 0;
            }
        }

        // Get available designations for filter
        $designations = DB::table('royalty_level_rewards')
            ->pluck('level')
            ->toArray();

        // Apply filters if requested
        if ($request->hasAny(['designation', 'status', 'purchase_status', 'start_date', 'end_date'])) {
            $downlineUsers = $this->applyFilters($downlineUsers, $request);
        }

        // Paginate results (15 per page)
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 15;
        $currentItems = array_slice($downlineUsers, ($currentPage - 1) * $perPage, $perPage);
        $paginatedUsers = new LengthAwarePaginator($currentItems, count($downlineUsers), $perPage, $currentPage, [
            'path' => LengthAwarePaginator::resolveCurrentPath()
        ]);

        return view('user.network.summary', compact('paginatedUsers', 'designations', 'breadcrumbs'));
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

        // Filter by date range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $users = array_filter($users, function ($user) use ($request) {
                $userDate = $user->user_doa ?? $user->created_at;
                return $userDate >= $request->start_date && $userDate <= $request->end_date;
            });
        }

        return array_values($users); // Reset array keys
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

        $breadcrumbs = [
            ['title' => 'Network', 'url' => route('user.view.userTree')],
            ['title' => 'Direct Team', 'url' => route('user.direct.team')]
        ];

        return view('user.network.direct-team', compact('directTeam', 'breadcrumbs'));
    }
}
