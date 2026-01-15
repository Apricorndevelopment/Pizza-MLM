<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Package2Purchase;
use App\Models\PointsTransaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessMaturityPackages extends Command
{
    protected $signature = 'maturity:process-payout';
    protected $description = 'Process maturity package payouts after 3 years - ₹220,000 per quantity';

    public function handle()
    {
        $currentDate = Carbon::now();
        $this->info("Processing maturity package payouts as of: " . $currentDate->format('Y-m-d H:i:s'));

        // Get maturity packages that are exactly 3 years old and haven't been paid out yet
        $maturityPackages = Package2Purchase::where('maturity', 1)
            ->where('payout_processed', 0) // Not paid out yet
            ->where('purchased_at', '<=', $currentDate->copy()->subYears(3))
            ->with('user')
            ->get();

        $this->info("Found " . $maturityPackages->count() . " maturity packages eligible for payout.");

        $processedCount = 0;
        $errorCount = 0;

        foreach ($maturityPackages as $package) {
            try {
                // Skip if no user associated
                if (!$package->user) {
                    $this->warn("Skipping package {$package->id}: No user found");
                    continue;
                }

                // Calculate payout amount: ₹220,000 × quantity
                $payoutAmount = 220000 * $package->quantity;

                DB::beginTransaction();

                // Add payout to user's balance
                $package->user->increment('wallet1_balance', $payoutAmount);

                // Record points transaction
                PointsTransaction::create([
                    'user_id' => $package->user->id,
                    'user_ulid' => $package->user->ulid,
                    'points' => $payoutAmount,
                    'notes' => 'Maturity package payout after 3 years: ' . $package->package_name . 
                              ' (Qty: ' . $package->quantity . ' × ₹220,000)',
                    'admin_id' => null,
                ]);

                // Mark package as paid out with all details
                $package->update([
                    'payout_processed' => 1,
                    'payout_amount' => $payoutAmount,
                ]);

                DB::commit();

                $this->info("✅ Processed package {$package->id}: User {$package->user->name} received ₹{$payoutAmount}");
                $processedCount++;

            } catch (\Exception $e) {
                DB::rollBack();
                $this->error("❌ Failed to process package {$package->id}: " . $e->getMessage());
                Log::error("Maturity payout failed for package {$package->id}: " . $e->getMessage());
                $errorCount++;
            }
        }

        $this->info("Maturity package payouts completed! Processed: {$processedCount}, Errors: {$errorCount}");
    }
}