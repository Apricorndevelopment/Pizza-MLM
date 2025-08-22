<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\CheckRewardsForAllUsers::class,
        \App\Console\Commands\DistributePackageProfits::class,
        \App\Console\Commands\ProcessMaturityPackages::class, // Add this line
    ];

    protected function schedule(Schedule $schedule)
    {
        // Rewards check - every minute
        $schedule->command('check:rewards')
            ->everyMinute()
            ->appendOutputTo(storage_path('logs/rewards_check.log'));

        // Monthly profits distribution - 1st of every month at midnight
        $schedule->command('profits:distribute-monthly')
            ->monthlyOn(1, '00:00')
            ->appendOutputTo(storage_path('logs/monthly_profits.log'));

        // Maturity payout - daily at specific time
        $schedule->command('maturity:process-payout')
            ->dailyAt('02:00') // 2 AM daily
            ->appendOutputTo(storage_path('logs/maturity_payout.log'));
    }

    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');
        
        require base_path('routes/console.php');
    }
}