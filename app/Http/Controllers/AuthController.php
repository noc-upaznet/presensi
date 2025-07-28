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
        // Validasi form
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Ambil inputan login
        $credentials = $request->only('email', 'password');

        // Coba login
        if (Auth::attempt($credentials)) {
            // Amankan session dari fixation
            $request->session()->regenerate();

            $user = Auth::user();

            // Jika password masih default (expired)
            if ($user->password_expired) {
                session()->flash('warning', 'Password Anda masih default, silakan ganti password terlebih dahulu.');
                return redirect()->route('ganti-password');
            }

            // Login sukses, arahkan ke dashboard atau intended URL
            return redirect()->intended('/dashboard');
        }

        // Login gagal
        return back()
            ->withErrors(['email' => 'Email atau password salah.'])
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
