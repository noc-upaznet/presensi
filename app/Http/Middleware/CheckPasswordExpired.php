<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPasswordExpired
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->password_expired) {
            // Jika bukan halaman ganti-password, redirect dan kasih flash message
            if (!$request->is('ganti-password')) {
                session()->flash('warning', 'Akses ditolak. Anda wajib mengganti password terlebih dahulu.');
                return redirect()->route('ganti-password');
            }
        }
        return $next($request);
    }
}
