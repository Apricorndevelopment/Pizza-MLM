<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DirectIncome;
use App\Models\BonusIncome;
use App\Models\LevelIncome;
use App\Models\PercentageReward;
use App\Models\RewardsIncome;
use App\Models\RepurchaseIncome;

class UserIncomeController extends Controller
{
    /**
     * 1. Direct Income Report
     */
    public function directIncome()
    {
        $incomes = DirectIncome::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('user.incomes.direct', compact('incomes'));
    }

    /**
     * 2. Bonus Income Report
     */
    public function bonusIncome()
    {
        $incomes = BonusIncome::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('user.incomes.bonus', compact('incomes'));
    }

    /**
     * 3. Level Income Report
     */
    public function levelIncome()
    {
        $incomes = LevelIncome::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('user.incomes.level', compact('incomes'));
    }

    /**
     * 4. Reward Income Report
     */
    public function rewardIncome()
    {
        $user = Auth::user();

        // 1. Fetch ALL available levels/rewards sorted by target
        $allRewards = PercentageReward::orderBy('achievement', 'asc')->get();

        // 2. Fetch specific dates for rewards the user has ALREADY earned
        // Key = reward_id, Value = created_at date
        $earnedRewards = RewardsIncome::where('user_id', $user->id)
            ->pluck('created_at', 'reward_id');

        return view('user.incomes.reward', compact('allRewards', 'earnedRewards', 'user'));
    }

    /**
     * 5. Repurchase Income Report
     * Note: Based on your schema, user_id is varchar(50), 
     * so we assume it stores the User's ULID.
     */
    public function repurchaseIncome()
    {
        // Using ULID because schema 'user_id' is varchar
        $incomes = RepurchaseIncome::where('user_id', Auth::user()->id)
            ->latest()
            ->paginate(10);

        return view('user.incomes.repurchase', compact('incomes'));
    }
}
