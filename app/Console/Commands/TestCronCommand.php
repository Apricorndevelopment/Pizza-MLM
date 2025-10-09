<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;

class TestCronCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'A dummy command to test cron jobs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        \Log::info('Test cron job executed successfully at ' . now());

        return 0;
    }
}
