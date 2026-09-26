<?php

namespace App\Http\Controllers;

use App\Models\User;
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
        $rememberForm = $request->filled('remember_form');

        $user = User::where('email', $request->email)->first();

        if ($user && $user->status != 1) {
            return back()->withErrors([
                'email' => 'Akun Anda tidak aktif. Silakan hubungi administrator.',
            ]);
        }

        $credentials = $request->only('email', 'password');
        $credentials['status'] = 1;

        if (Auth::attempt($credentials, $request->filled('remember'))) {

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
                session()->flash(
                    'warning',
                    'Password Anda masih default, silakan ganti password terlebih dahulu.'
                );
                return redirect()->route('ganti-password');
            }

            return $user->hasRole('admin')
                ? redirect()->route('dashboard')
                : redirect()->route('clock-in');
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
