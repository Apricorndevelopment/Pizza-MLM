<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsVendor
{
    public function handle(Request $request, Closure $next)
    {
        // अगर यूजर वेंडर है (is_vendor = 1) तो आगे बढ़ने दें
        if (Auth::check() && Auth::user()->is_vendor == 1) {
            return $next($request);
        }
        // अगर वेंडर नहीं है, तो यूजर डैशबोर्ड पर भेज दें
        return redirect()->route('user.dashboard')->with('error', 'Access Denied! Vendor area only.');
    }
}