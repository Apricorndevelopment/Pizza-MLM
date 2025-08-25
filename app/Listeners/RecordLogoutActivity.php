<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use App\Models\LoginActivity;

class RecordLogoutActivity
{
    public function handle(Logout $event)
    {
        if ($event->user) {
            LoginActivity::where('user_id', $event->user->id)
                ->where('session_id', session()->getId())
                ->whereNull('logout_time')
                ->update(['logout_time' => now()]);
        }
    }
}