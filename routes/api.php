<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

Route::post('/login', function (Request $request) {
    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Login gagal'], 401);
    }

    // ✅ Bikin token dengan Sanctum
    $token = $user->createToken('web-token')->accessToken;

    return response()->json([
        'token' => $token,
        'user' => $user,
    ]);
});