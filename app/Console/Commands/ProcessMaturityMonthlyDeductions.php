<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Package2Purchase;
use App\Models\PointsTransaction;
use App\Models\User;
use App\Models\MaturityMonthlyDeduction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessMaturityMonthlyDeductions extends Command
{
    protected $signature = 'maturity:process-monthly-deductions';
    protected $description = 'Process monthly deductions for maturity packages';

    public function handle()
    {
        $currentDate = Carbon::now();
        $this->info("Processing monthly maturity package deductions as of: " . $currentDate->format('Y-m-d H:i:s'));

        // Get active maturity packages that haven't completed 3 years
        $maturityPackages = Package2Purchase::where('maturity', 1)
            ->where('payout_processed', 0) // Not paid out yet
            ->where('purchased_at', '>', $currentDate->copy()->subYears(3)) // Within 3 years
            ->with('user')
            ->get();

        $this->info("Found " . $maturityPackages->count() . " active maturity packages for monthly deduction.");

        $processedCount = 0;
        $errorCount = 0;
        $penaltyCount = 0;

        foreach ($maturityPackages as $package) {
            try {
                // Skip if no user associated
                if (!$package->user) {
                    $this->warn("Skipping package {$package->id}: No user found");
                    continue;
                }

                // Check if this month's deduction already processed
                $currentMonth = $currentDate->format('Y-m');
                $alreadyDeducted = MaturityMonthlyDeduction::where('package2_purchase_id', $package->id)
                    ->where('deduction_month', $currentMonth)
                    ->exists();

                if ($alreadyDeducted) {
                    $this->info("Skipping package {$package->id}: Already deducted for {$currentMonth}");
                    continue;
                }

                // Calculate months remaining
                $purchaseDate = Carbon::parse($package->purchased_at);
                $monthsPassed = $purchaseDate->diffInMonths($currentDate);
                $monthsRemaining = 36 - $monthsPassed; // 3 years = 36 months

                if ($monthsRemaining <= 0) {
                    continue; // Skip if completed 3 years
                }

                $deductionAmount = $package->final_price;
                $user = $package->user;

                DB::beginTransaction();

                // Check if user has sufficient balance
                if ($user->wallet1_balance >= $deductionAmount) {
                    // Sufficient balance - deduct normally
                    $user->decrement('wallet1_balance', $deductionAmount);
                    $penalty = 0;
                    $status = 'paid';
                    $notes = "Monthly deduction for maturity package: {$package->package_name}";

                    // Record points transaction
                    PointsTransaction::create([
                        'user_id' => $user->id,
                        'user_ulid' => $user->ulid,
                        'points' => -$deductionAmount,
                        'notes' => $notes,
                        'admin_id' => null,
                    ]);

                } else {
                    // Insufficient balance - apply penalty (deduct 10% of deduction amount)
                    $penalty = $deductionAmount * 0.10; // 10% penalty
                    $status = 'pending';
                      $notes = "Monthly deduction pending for maturity package: {$package->package_name} - Insufficient balance";
                    $penaltyCount++;
                }

                // Record monthly deduction
                MaturityMonthlyDeduction::create([
                    'user_id' => $user->id,
                    'user_ulid' => $user->ulid,
                    'package2_purchase_id' => $package->id,
                    'deduction_amount' => $deductionAmount,
                    'penalty_amount' => $penalty,
                    'total_deduction' => $deductionAmount + $penalty,
                    'deduction_month' => $currentMonth,
                    'months_remaining' => $monthsRemaining,
                    'status' => $status,
                    'deducted_at' => $currentDate
                ]);

                DB::commit();

                $this->info("Processed package {$package->id}: Status - {$status}");
                $processedCount++;
            } catch (\Exception $e) {
                DB::rollBack();
                $this->error("❌ Failed to process deduction for package {$package->id}: " . $e->getMessage());
                Log::error("Maturity monthly deduction failed for package {$package->id}: " . $e->getMessage());
                $errorCount++;
            }
        }

        $this->info("Monthly maturity deductions completed! Processed: {$processedCount}, Penalties: {$penaltyCount}, Errors: {$errorCount}");
    }
}