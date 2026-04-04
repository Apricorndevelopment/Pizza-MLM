<?php

namespace App\Services;

use App\Models\User;
use App\Models\Order;
use App\Models\AutoPoolCategory;
use App\Models\AutoPool;
use App\Models\UserAutopoolTracker;
use App\Models\AutopoolEarningsHistory;
use App\Models\Wallet1Transaction;
use App\Models\Wallet2Transaction; 
use App\Models\PercentageIncome; 
use App\Models\LevelIncome; 
use App\Models\RepurchaseIncome; 
use Carbon\Carbon; 

class AutoPoolService
{
    public function processFirstPurchase(User $user, $totalPV, $adminId, $order = null)
    {
        $this->initializeTracker($user);
        $this->incrementSponsorDirect($user->sponsor_id, $totalPV, $order);
        $this->distributeSingleLegPV($user->parent_id, $totalPV, $adminId);
        $this->tryUnlockNextCategory($user->autopoolTracker, $order);
    }

    public function processRepurchase(User $user, $totalPV, $order = null)
    {
        $tracker = $this->initializeTracker($user);

        $tracker->category_repurchase_pv += $totalPV;
        $tracker->save();

        // 1. Check if this order contains a package to unlock a NEW category
        $this->tryUnlockNextCategory($tracker, $order);

        // 2. Check if this PV fulfilled the condition for a STUCK Last Pool
        $this->processPoolRewards($tracker, null);
    }

    private function distributeSingleLegPV($parentUlid, $pv, $adminId)
    {
        $currentParentUlid = $parentUlid;

        while ($currentParentUlid) {
            $parent = User::where('ulid', $currentParentUlid)->first();
            if (!$parent) break;

            $tracker = $this->initializeTracker($parent);

            if ($tracker && !$tracker->is_locked) {
                $tracker->single_leg_pv += $pv;
                $tracker->save();

                $this->processPoolRewards($tracker, $adminId);
            }

            $currentParentUlid = $parent->parent_id;

            if ($currentParentUlid === $parent->ulid) break;
        }
    }

    private function incrementSponsorDirect($sponsorUlid, $directPV, $currentOrder)
    {
        $sponsor = User::where('ulid', $sponsorUlid)->first();
        if ($sponsor) {
            $tracker = $this->initializeTracker($sponsor);
            
            // सिर्फ तभी डायरेक्ट काउंट होगा जब स्पॉन्सर किसी कैटेगरी में हो
            if ($tracker->current_category_id) {
                $category = AutoPoolCategory::find($tracker->current_category_id);
                if ($category) {
                    $requiredPV = $category->each_direct_pv ?? 0;
                    
                    if ($directPV >= $requiredPV) {
                        $tracker->category_directs_count += 1;
                        $tracker->save();
                        
                        // Check if this new direct fulfilled the condition for a STUCK Last Pool
                        $this->processPoolRewards($tracker, null);
                    }
                }
            }
        }
    }

    /**
     * DYNAMIC UNLOCK LOGIC: Product-Based Target Lock-On (NO CONDITIONS CHECKED HERE)
     */
   /**
     * DYNAMIC UNLOCK LOGIC: Product-Based Target Lock-On (NO CONDITIONS CHECKED HERE)
     */
    public function tryUnlockNextCategory(UserAutopoolTracker $tracker, $currentOrder = null)
    {
        // 1. अगर यूज़र पहले से किसी पूल में ACTIVE है, तो कोई नया टारगेट नहीं बनेगा।
        if (!$tracker->is_locked) {
            return;
        }

        // 2. अगर यूज़र ब्लैंक है (current_category_id NULL है), तो उसे नए प्रोडक्ट के आधार पर पूल में डालो
        if (!$tracker->current_category_id) {
            if (!$currentOrder) return; 

            $productIds = $currentOrder->items->pluck('product_id')->toArray();
            
            // =========================================================
            // NEW: चेक करो कि यूज़र ने कौन-कौन सी कैटेगरी पहले ही जीत ली हैं
            // =========================================================
            $completedCategoryIds = AutopoolEarningsHistory::where('user_id', $tracker->user_id)
                                        ->pluck('category_id')
                                        ->unique()
                                        ->toArray();
            
            // चेक करो कि खरीदे गए प्रोडक्ट्स में से कौन सा Auto Pool का पैकेज है 
            // और यह पक्का करो कि वो कैटेगरी पहले पूरी न हुई हो (whereNotIn)
            $matchedCategory = AutoPoolCategory::whereIn('product_package_id', $productIds)
                                               ->whereNotIn('id', $completedCategoryIds) // <--- PREVENT RE-ENTRY
                                               ->where('is_active', 1)
                                               ->first();

            if ($matchedCategory) {
                // सीधे पूल में एंट्री दे दो (Entry Conditions हटा दी गई हैं)
                $firstPool = AutoPool::where('category_id', $matchedCategory->id)->orderBy('pool_level', 'asc')->first();

                if ($firstPool) {
                    $tracker->current_category_id = $matchedCategory->id;
                    $tracker->current_pool_id = $firstPool->id;
                    $tracker->is_locked = false; // पूल चालू!
                    $tracker->save();

                    // अगर पहले से सिंगल लेग PV जमा है, तो उसे प्रोसेस करो
                    $this->processPoolRewards($tracker, null);
                }
            }
        }
    }

   private function processPoolRewards(UserAutopoolTracker $tracker, $adminId)
    {
        if ($tracker->is_locked || !$tracker->current_pool_id) return;

        $currentPool = AutoPool::find($tracker->current_pool_id);
        if (!$currentPool) return;

        while ($currentPool && $tracker->single_leg_pv >= $currentPool->required_pv) {
            
            // ==========================================
            // LAST POOL CONDITION CHECK LOGIC
            // ==========================================
            $nextPool = AutoPool::where('category_id', $currentPool->category_id)
                                ->where('pool_level', '>', $currentPool->pool_level)
                                ->orderBy('pool_level', 'asc')
                                ->first();
            
            $isLastPool = !$nextPool; // अगर nextPool नहीं है, तो ये आख़िरी पूल है

            if ($isLastPool) {
                $category = AutoPoolCategory::find($tracker->current_category_id);
                $conditionMet = false;

                if ($category) {
                    if ($category->pv_required == 0 && $category->direct_count == 0) {
                        $conditionMet = true; // Free Entry (No condition)
                    } elseif ($category->pv_required > 0 && $tracker->category_repurchase_pv >= $category->pv_required) {
                        $conditionMet = true;
                        $tracker->category_repurchase_pv -= $category->pv_required; // PV इस्तेमाल हो गई
                    } elseif ($category->direct_count > 0 && $tracker->category_directs_count >= $category->direct_count) {
                        $conditionMet = true;
                        $tracker->category_directs_count -= $category->direct_count; // Directs इस्तेमाल हो गए
                    }
                }

                // अगर कंडीशन पूरी नहीं हुई, तो लूप यहीं रोक दो (Reward होल्ड पर चला जाएगा)
                if (!$conditionMet) {
                    break; // Break the while loop. Tracker retains PV.
                }
            }
            // ==========================================

            $user = $tracker->user;
            $calculatedIncome = $currentPool->income;
            
            // ==========================================
            // CAPPING LOGIC FOR AUTO POOL
            // ==========================================
            $dailyLimit = $user->capping_limit;

            $todayLevelIncome = LevelIncome::where('user_id', $user->id)->whereDate('created_at', Carbon::today())->sum('amount');
            $todayRepurchaseIncome = RepurchaseIncome::where('user_id', $user->id)->whereDate('created_at', Carbon::today())->sum('commission');
            $todayAutoPoolIncome = AutopoolEarningsHistory::where('user_id', $user->id)->whereDate('created_at', Carbon::today())->sum('reward_amount');
            
            $percentageSettings = PercentageIncome::first();

            $totalEarnedToday = $todayLevelIncome + $todayRepurchaseIncome + $todayAutoPoolIncome;
            $payableAmount = 0;

            if ($user->is_capping_enabled == 1 && $dailyLimit > 0) {
                if ($totalEarnedToday >= $dailyLimit) {
                    $payableAmount = 0;
                } elseif (($totalEarnedToday + $calculatedIncome) > $dailyLimit) {
                    $payableAmount = $dailyLimit - $totalEarnedToday;
                } else {
                    $payableAmount = $calculatedIncome;
                }
            } else {
                $payableAmount = $calculatedIncome; 
            }
            
            // ==========================================
            // WALLET 1 & WALLET 2 DISTRIBUTION
            // ==========================================
            if ($payableAmount > 0 && $percentageSettings) {
                $wallet1Amount = $payableAmount * ($percentageSettings->personal_wallet / 100);
                $wallet2Amount = $payableAmount * ($percentageSettings->second_wallet / 100);
                
                $user->wallet1_balance += $wallet1Amount;
                $user->wallet2_balance += $wallet2Amount;
                $user->current_pool_rank = $currentPool->rank_name;
                $user->save();

                if ($wallet1Amount > 0) {
                    Wallet1Transaction::create([
                        'user_id'   => $user->id,
                        'user_ulid' => $user->ulid,
                        'wallet1'   => $wallet1Amount,
                        'notes'     => "Auto Pool Reward: {$currentPool->rank_name} (Category ID: {$currentPool->category_id}) (W1)",
                        'balance'   => $user->wallet1_balance,
                    ]);
                }

                if ($wallet2Amount > 0) {
                    Wallet2Transaction::create([
                        'user_id'   => $user->id,
                        'user_ulid' => $user->ulid,
                        'wallet2'   => $wallet2Amount,
                        'notes'     => "Auto Pool Reward: {$currentPool->rank_name} (Category ID: {$currentPool->category_id}) (W2)",
                        'balance'   => $user->wallet2_balance,
                    ]);
                }
            } else {
                $user->current_pool_rank = $currentPool->rank_name;
                $user->save();
            }

            AutopoolEarningsHistory::create([
                'user_id' => $user->id,
                'category_id' => $currentPool->category_id,
                'pool_id' => $currentPool->id,
                'reward_amount' => $payableAmount, 
                'rank_name_achieved' => $currentPool->rank_name,
            ]);

            $tracker->single_leg_pv -= $currentPool->required_pv;
            
            if ($nextPool) {
                $tracker->current_pool_id = $nextPool->id;
                $currentPool = $nextPool; 
            } else {
                // ==============================================
                // CATEGORY PURI KHATAM HO GAYI!
                // ==============================================
                $tracker->is_locked = true;
                $tracker->current_pool_id = null;
                $tracker->current_category_id = null; 
                
                // NEW: PV aur Directs dono ko 0 karna zaroori hai fresh start ke liye
                $tracker->single_leg_pv = 0; 
                $tracker->category_directs_count = 0; 
                $tracker->category_repurchase_pv = 0; // <--- ADDED THIS LINE
                
                $tracker->save();

                break; 
            }
        }
        $tracker->save();
    }

    public function initializeTracker(User $user)
    {
        $tracker = UserAutopoolTracker::firstOrCreate(
            ['user_id' => $user->id],
            [
                'single_leg_pv' => 0,
                'category_repurchase_pv' => 0,
                'category_directs_count' => 0,
                'is_locked' => true,
                'current_category_id' => null, 
                'current_pool_id' => null
            ]
        );

        return $tracker;
    }
}