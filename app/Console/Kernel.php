<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\CheckRewardsForAllUsers;
use App\Console\Commands\DistributePackageProfits;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        CheckRewardsForAllUsers::class,
        DistributePackageProfits::class,
    ];

    protected function schedule(Schedule $schedule)
    {

        $schedule->command('check:rewards')
            ->everyMinute()
            ->appendOutputTo(storage_path('logs/rewards_check.log'));

        $schedule->command('profits:distribute-monthly')
            ->monthlyOn(1, '00:00');
    }

    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');
    }
}
