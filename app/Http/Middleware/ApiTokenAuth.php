<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiTokenAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak ditemukan.',
            ], 401);
        }

        $user = User::where('api_token', hash('sha256', $token))
            ->where('status', 1)
            ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak valid.',
            ], 401);
        }

        // Periksa masa berlaku token
        if (
            !$user->expires_at ||
            now()->greaterThanOrEqualTo($user->expires_at)
        ) {
            $user->forceFill([
                'api_token' => null,
                'expires_at' => null,
            ])->save();

            return response()->json([
                'success' => false,
                'message' => 'Sesi berakhir. Silakan login kembali.',
            ], 401);
        }

        // Perpanjang masa berlaku setiap ada request valid
        $expiresAt = now()->addMinutes(1);

        $user->forceFill([
            'expires_at' => $expiresAt,
        ])->save();

        // Simpan user agar bisa digunakan controller
        $request->setUserResolver(fn() => $user);

        return $next($request);
    }
}
