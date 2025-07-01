<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class TokenAuth
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->has('token')) {
            $token = $request->token;
            Session::put('token', $token);

            // Request ke Auth Server untuk ambil data user dari token
            $response = Http::withToken($token)->get('http://127.0.0.1:8000/api/user');

            if ($response->ok()) {
                $user = $response->json();
                Session::put('user', $user);
            } else {
                Session::forget('token');
                Session::forget('user');
            }

            // Redirect tanpa query param token
            return redirect($request->url());
        }

        if (!Session::has('user') || !Session::has('token')) {
            Session::put('redirect_after_login', $request->fullUrl());
            return redirect('/login');
        }

        return $next($request);
    }
}
