<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\DB;
use App\Models\LoginActivity;
use Jenssegers\Agent\Agent;

class RecordLoginActivity
{
    public function handle(Login $event)
    {
        $agent = new Agent();
        
        LoginActivity::create([
            'user_id' => $event->user->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'device_type' => $this->getDeviceType($agent),
            'browser' => $agent->browser(),
            'platform' => $agent->platform(),
            'session_id' => session()->getId(),
            'login_time' => now(),
        ]);

        // Also update sessions table
        DB::table('sessions')
            ->where('id', session()->getId())
            ->update([
                'user_id' => $event->user->id,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
    }

    private function getDeviceType($agent)
    {
        if ($agent->isMobile()) return 'mobile';
        if ($agent->isTablet()) return 'tablet';
        return 'desktop';
    }
}