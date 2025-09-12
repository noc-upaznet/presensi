<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $rememberForm = $request->filled('remember_form');

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            // Simpan ke cookie kalau user minta
            if ($rememberForm) {
                cookie()->queue(cookie('login_email', $request->email, 43200));
                cookie()->queue(cookie('login_password', $request->password, 43200));
            } else {
                cookie()->queue(cookie('login_email', '', -1));
                cookie()->queue(cookie('login_password', '', -1));
            }
            $user = Auth::user();
            // Jika password masih default (expired)
            if ($user->password_expired) {
                session()->flash('warning', 'Password Anda masih default, silakan ganti password terlebih dahulu.');
                return redirect()->route('ganti-password');
            }

            if ($user->hasRole('admin')) {
                return redirect()->route('dashboard');
            } else {
                return redirect()->route('clock-in');
            }

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}