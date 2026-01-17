<?php

namespace App\Console\Commands;

use App\Models\LevelIncome;
use Illuminate\Console\Command;
use App\Models\ProductPackagePurchase;
use App\Models\PackageMonthlyDistribution;
use App\Models\User;
use Carbon\Carbon;


class DistributePackageProfits extends Command
{
    protected $signature = 'profits:distribute-monthly';
    protected $description = 'Distribute monthly package profits to users';

    protected function processLevelIncome($user, $amount, $package)
    {
        // Only process if package has time duration
        if (empty($package->time)) {
            return;
        }

        $currentDate = now();
        $expiryDate = Carbon::parse($package->purchased_at)->addYears($package->time);

        // Skip if package has expired
        if ($currentDate > $expiryDate) {
            return;
        }

        $currentLevel = 1;
        $currentUser = $user;

        $levelConfigs = collect([
            ['level' => 1, 'percentage' => 10.00, 'required_rank' => null],
            ['level_range' => '2-10', 'percentage' => 5.00, 'required_rank' => 'Farmer'],
            ['level_range' => '11-15', 'percentage' => 3.00, 'required_rank' => 'Silver Farmer'],
            ['level_range' => '16-20', 'percentage' => 1.00, 'required_rank' => 'Gold Farmer'],
            ['level_range' => '21-25', 'percentage' => 0.50, 'required_rank' => 'Platinum Farmer'],
            ['level_range' => '26-30', 'percentage' => 0.25, 'required_rank' => 'Ruby Farmer'],
            ['level_range' => '31-35', 'percentage' => 0.25, 'required_rank' => 'Sapphire Farmer'],
            ['level_range' => '36-40', 'percentage' => 0.25, 'required_rank' => 'Diamond Farmer'],
            ['level_range' => '41-45', 'percentage' => 0.25, 'required_rank' => 'Blue Diamond Farmer'],
            ['level_range' => '46-50', 'percentage' => 0.25, 'required_rank' => 'Black Diamond Farmer'],
        ]);

        // Calculate monthly amount based on package rate
        $monthlyAmount = $package->final_price * ($package->rate / 100);

        while ($currentUser->sponsor_id && $currentLevel <= 50) {
            $sponsor = User::where('ulid', $currentUser->sponsor_id)->first();
            if (!$sponsor) break;

            // Determine percentage and required rank based on level
            $percentage = 0;
            $eligible = false;
            $requiredRank = null;

            foreach ($levelConfigs as $config) {
                if (isset($config['level']) && $currentLevel == $config['level']) {
                    $percentage = $config['percentage'];
                    $requiredRank = $config['required_rank'];
                    break;
                } elseif (isset($config['level_range'])) {
                    list($min, $max) = explode('-', $config['level_range']);
                    if ($currentLevel >= $min && $currentLevel <= $max) {
                        $percentage = $config['percentage'];
                        $requiredRank = $config['required_rank'];
                        break;
                    }
                }
            }

            // Check if sponsor meets rank requirement
            if ($requiredRank === null || $sponsor->current_rank === $requiredRank) {
                $eligible = true;
            }

            if ($percentage > 0 && $eligible) {
                $incomeAmount = $monthlyAmount * ($percentage / 100);

                // Add to user's balance
                $sponsor->increment('wallet1_balance', $incomeAmount);

                // Record in level income table
                LevelIncome::create([
                    'user_id' => $sponsor->id,
                    'user_ulid' => $sponsor->ulid,
                    'from_user_id' => $user->id,
                    'from_user_ulid' => $user->ulid,
                    'from_user_name' => $user->name,
                    'purchase_amount' => $package->final_price,
                    'rate' => $package->rate,
                    'level' => $currentLevel,
                    'percentage' => $percentage,
                    'amount' => $incomeAmount,
                    'package_id' => $package->id ?? null,
                    'package_name' => $package->package_name ?? null,
                    'distribution_date' => $currentDate,
                    'months_remaining' => $currentDate->diffInMonths($expiryDate)
                ]);
            }

            $currentUser = $sponsor;
            $currentLevel++;
        }
    }

    public function handle()
    {
        $currentDate = Carbon::now();
        $currentMonth = Carbon::now()->format('Y-m');

        // Get active package purchases (where time hasn't expired)
        $purchases = ProductPackagePurchase::where('maturity', 0)
            ->where('purchased_at', '<=', $currentDate)
            ->where('rate', '>', 0)
            ->with('user')
            ->get();

        foreach ($purchases as $purchase) {

            $expiryDate = $purchase->time
                ? Carbon::parse($purchase->purchased_at)->addYears((int) $purchase->time)
                : null;

            if ($purchase->time && $currentDate->greaterThan($expiryDate)) {
                continue;
            }
            // dd($expiryDate, $currentDate, $purchase->purchased_at, $purchase->time);
            if (!$purchase->user) {
                continue;
            }
            // Calculate monthly profit
            $monthlyProfit = $purchase->final_price * ($purchase->rate / 100);

            // Update user's balance
            $purchase->user->increment('wallet1_balance', $monthlyProfit);

            // Record distribution
            PackageMonthlyDistribution::create([
                'user_id' => $purchase->user_id,
                'user_ulid' => $purchase->user->ulid,
                'package2_purchase_id' => $purchase->id,
                'purchase_amount' => $purchase->final_price,
                'rate_percentage' => $purchase->rate,
                'distributed_amount' => $monthlyProfit,
                'months_remaining' => $this->calculateRemainingMonths($purchase),
                'distribution_date' => Carbon::now()
            ]);
            $this->processLevelIncome($purchase->user, $purchase->final_price, $purchase);
        }

        $this->info('Monthly package profits distributed successfully!');
    }

    protected function calculateRemainingMonths($purchase)
    {
        if (empty($purchase->time)) {
            return null; // Lifetime package
        }

        $expiryDate = Carbon::parse($purchase->purchased_at)->addYears($purchase->time);
        return Carbon::now()->diffInMonths($expiryDate);
    }
}
