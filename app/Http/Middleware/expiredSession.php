<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class expiredSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    public function handle(Request $request, Closure $next)
    {
        dd('masuk middleware session');
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Session Anda telah habis. Silakan login kembali.');
        }

        return $next($request);
    }
}
