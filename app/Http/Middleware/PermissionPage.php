<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PermissionPage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::user() || !Auth::user()->can('payroll-view-admin')) {
            return redirect('/clock-in');
        }

        if (!Auth::user() || !Auth::user()->can('karyawan-view')) {
            return redirect('/clock-in');
        }

        if (!Auth::user() || !Auth::user()->can('jadwal-shift-create')) {
            return redirect('/clock-in');
        }

        if (!Auth::user() || !Auth::user()->can('manage-user')) {
            return redirect('/clock-in');
        }

        if (!Auth::user() || !Auth::user()->can('dashboard-view')) {
            return redirect('/clock-in');
        }
        return $next($request);
    }
}
