<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Diamond
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        
        // Get all ranks from sr_no 7 to 13 (Diamond Farmer and above)
        $eligibleRanks = DB::table('royalty_level_rewards')
            ->whereBetween('sr_no', [7, 13])
            ->pluck('level')
            ->toArray();

        if (!in_array($user->current_rank, $eligibleRanks)) {
            return redirect()->back()->with('error', 'You must be Diamond Farmer rank or above to access this feature.');
        }

        return $next($request);
    }
}