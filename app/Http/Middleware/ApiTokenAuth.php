<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiTokenAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $user = User::where('api_token', hash('sha256', $token))
            ->where('status', 1)
            ->first();

        if (!$user) {
            return response()->json([
                'message' => 'Token tidak valid'
            ], 401);
        }

        auth()->setUser($user);

        return $next($request);
    }
}
