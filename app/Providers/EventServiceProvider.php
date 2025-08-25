<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    protected $listen = [
        'Illuminate\Auth\Events\Login' => [
            'App\Listeners\RecordLoginActivity',
        ],
        'Illuminate\Auth\Events\Logout' => [
            'App\Listeners\RecordLogoutActivity',
        ],
    ];
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
