<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\LoginActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginActivityController extends Controller
{
    public function index()
    {
        $activities = LoginActivity::where('user_id', Auth::id())
            ->orderBy('login_time', 'desc')
            ->paginate(10);

            $breadcrumbs = [
                ['title' => 'Login Activity', 'url' => '']
            ];
        return view('user.login-activity', compact('activities','breadcrumbs'));
    }

    public function destroy($id)
    {
        $activity = LoginActivity::where('user_id', Auth::id())->findOrFail($id);
        $activity->delete();

        return redirect()->back()->with('success', 'Login activity deleted successfully.');
    }

    public function logoutAllDevices()
    {
        // Invalidate all sessions except current one
        LoginActivity::where('user_id', Auth::id())
            ->where('session_id', '!=', session()->getId())
            ->whereNull('logout_time')
            ->update(['logout_time' => now()]);

        // You can also implement session invalidation logic here

        return redirect()->back()->with('success', 'Logged out from all other devices.');
    }
}