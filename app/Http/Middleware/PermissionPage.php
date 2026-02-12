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
        $user = Auth::user();
        if (!$user || !$user->hasAnyPermission([
            'payroll-view-admin',
            'karyawan-view',
            'jadwal-shift-create',
            'manage-user',
            'dashboard-view',
            'kasbon-view'
        ])) {
            return redirect('/clock-in');
        }
        return $next($request);
    }
}
