<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Models\User;

class SSOLoginController extends Controller
{
    public function callback(Request $request)
    {
        $token = $request->query('token');

        if (!$token) {
            return redirect('http://127.0.0.1:8000/login');
        }

        // Ambil user dari Auth Server
        $response = Http::withToken($token)->get('http://127.0.0.1:8000/api/user');

        if ($response->ok()) {
            $remoteUser = $response->json();

            // Simpan/sinkron user ke DB lokal
            $localUser = User::updateOrCreate(
                ['email' => $remoteUser['email']],
                [
                    'name' => $remoteUser['name'],
                    'password' => bcrypt(Str::random()), // Password acak agar valid
                    'role' => 'admin', // Default role, bisa kamu sesuaikan
                ]
            );

            Auth::login($localUser);

            return redirect()->intended('/dashboard');
        }

        return redirect('http://127.0.0.1:8000/login')->withErrors(['Token invalid']);
    }
}
