<?php

use App\Http\Controllers\Api\KaryawanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

// Route::get('/karyawan/ulangtahun', [KaryawanController::class, 'ulangTahun']);



Route::post('/login', function (Request $request) {

    $validated = $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
    ]);

    $user = User::where('email', $validated['email'])
        ->where('status', 1)
        ->first();

    if (!$user || !Hash::check($validated['password'], $user->password)) {
        return response()->json([
            'success' => false,
            'message' => 'Email atau password salah.',
        ], 401);
    }

    // Generate token acak
    $token = bin2hex(random_bytes(32));

    // Simpan hash token ke database
    $user->forceFill([
        'api_token' => hash('sha256', $token),
    ])->save();

    return response()->json([
        'success' => true,
        'message' => 'Login berhasil.',
        'token_type' => 'Bearer',
        'token' => $token,
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'branch_id' => $user->branch_id,
            'current_role' => $user->current_role,
        ],
    ]);
});

Route::middleware('api.token')->group(function () {
    Route::get('/karyawan', [KaryawanController::class, 'index']);
    Route::get('/karyawan/ulangtahun', [KaryawanController::class, 'ulangTahun']);
});
