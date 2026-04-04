<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\DirectIncome;
use App\Models\BonusIncome;
use App\Models\CashbackIncome;
use App\Models\LevelIncome;
use App\Models\PercentageReward;
use App\Models\RewardsIncome;
use App\Models\RepurchaseIncome;
use App\Models\VendorIncome;
use App\Models\AutoPoolCategory;
use App\Models\AutopoolEarningsHistory;
use App\Models\Order;
use App\Services\AutoPoolService;

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

    public function bonusIncome()
    {
        $incomes = BonusIncome::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('user.incomes.bonus', compact('incomes'));
    }

    /**
     * 2. Cashback Income Report
     */
    public function cashbackIncome()
    {
        $incomes = CashbackIncome::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('user.incomes.cashback', compact('incomes'));
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

    public function vendorIncomeReport()
    {
        $user = Auth::user();

        // Current user ko unke direct vendors se aayi hui income
        $incomes = VendorIncome::where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('user.incomes.vendor_income', compact('incomes'));
    }

    // public function autoPoolProgress()
    // {
    //     $user = Auth::user();

    //     // 1. Ensure tracker is initialized
    //     $autoPoolService = new AutoPoolService();
    //     $tracker = $autoPoolService->initializeTracker($user);

    //     // Load current relationships
    //     $tracker->load(['currentCategory', 'currentPool']);

    //     // 2. Total Earnings from Auto Pool
    //     $totalEarnings = AutopoolEarningsHistory::where('user_id', $user->id)->sum('reward_amount');

    //     // 3. Fetch full roadmap (All Categories and their Pools)
    //     $categories = AutoPoolCategory::with(['pools' => function ($q) {
    //         $q->orderBy('pool_level', 'asc');
    //     }, 'package'])->orderBy('id', 'asc')->get();

    //     // 4. Check if required package is purchased (Only needed if locked)
    //     $hasPackage = true;
    //     if ($tracker->is_locked && $tracker->currentCategory && $tracker->currentCategory->product_package_id) {
    //         $hasPackage = Order::where('user_id', $user->id)
    //             ->whereIn('status', ['accepted', 'delivered'])
    //             ->whereHas('items', function ($q) use ($tracker) {
    //                 $q->where('product_id', $tracker->currentCategory->product_package_id);
    //             })->exists();
    //     }

    //     // Check if user has completed all available pools
    //     $isAllCompleted = false;
    //     if ($tracker->is_locked && !$tracker->current_pool_id) {
    //         $lastCategory = AutoPoolCategory::orderBy('id', 'desc')->first();
    //         if ($tracker->current_category_id && $lastCategory && $tracker->current_category_id == $lastCategory->id) {
    //             $isAllCompleted = true;
    //         }
    //     }

    //     return view('user.incomes.autopool_progress', compact('tracker', 'categories', 'totalEarnings', 'hasPackage', 'isAllCompleted'));
    // }

    public function autoPoolProgress()
    {
        $user = Auth::user();

        // 1. Ensure tracker is initialized
        $autoPoolService = new \App\Services\AutoPoolService();
        $tracker = $autoPoolService->initializeTracker($user);

        // Load current relationships
        $tracker->load(['currentCategory', 'currentPool']);

        // 2. Total Earnings from Auto Pool
        $totalEarnings = \App\Models\AutopoolEarningsHistory::where('user_id', $user->id)->sum('reward_amount');

        // 3. Fetch full roadmap (All Categories and their Pools)
        $categories = \App\Models\AutoPoolCategory::with(['pools' => function ($q) {
            $q->orderBy('pool_level', 'asc');
        }, 'package'])->orderBy('id', 'asc')->get();

        // 4. Check if required package is purchased (Only needed if locked and category exists)
        $hasPackage = true;
        if ($tracker->is_locked && $tracker->currentCategory && $tracker->currentCategory->product_package_id) {
            $hasPackage = \App\Models\Order::where('user_id', $user->id)
                ->whereIn('status', ['accepted', 'delivered'])
                ->whereHas('items', function ($q) use ($tracker) {
                    $q->where('product_id', $tracker->currentCategory->product_package_id);
                })->exists();
        }

        // ==============================================================
        // NEW DYNAMIC LOGIC: Check actual history instead of sequential order
        // ==============================================================
        $earnedPoolIds = \App\Models\AutopoolEarningsHistory::where('user_id', $user->id)->pluck('pool_id')->toArray();
        $completedCategoryIds = [];
        
        foreach ($categories as $cat) {
            $lastPool = $cat->pools->last();
            // If the user has earned the reward for the LAST pool of this category, it is completed.
            if ($lastPool && in_array($lastPool->id, $earnedPoolIds)) {
                // Only mark completed if they haven't re-entered it
                if ($tracker->current_category_id !== $cat->id) {
                    $completedCategoryIds[] = $cat->id;
                }
            }
        }

        // Check if user has completed all available categories in the system
        $isAllCompleted = (count($completedCategoryIds) === $categories->count() && $categories->count() > 0 && !$tracker->current_category_id);

        return view('user.incomes.autopool_progress', compact(
            'tracker', 'categories', 'totalEarnings', 'hasPackage', 'isAllCompleted', 'earnedPoolIds', 'completedCategoryIds'
        ));
    }
}
