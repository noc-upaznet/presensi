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

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Cek apakah password expired (masih default)
            if ($user->password_expired) {
                session()->flash('warning', 'Password Anda masih default, silakan ganti password terlebih dahulu.');
                return redirect()->route('ganti-password');
            }

            return redirect()->intended('/dashboard');
        }

        return back()
            ->withErrors(['email' => 'Masukkan email dan password yang benar.'])
            ->onlyInput('email');
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
