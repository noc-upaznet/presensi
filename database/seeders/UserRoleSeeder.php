<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Contoh: ambil semua user (atau ambil user tertentu)
        $users = User::all();

        foreach ($users as $user) {
            // Contoh: kalau user pertama, kasih role admin dan hr
            if ($user->id == 156) {
                UserRole::create(['user_id' => $user->id, 'role' => 'spv']);
                UserRole::create(['user_id' => $user->id, 'role' => 'hr']);
                // UserRole::create(['user_id' => $user->id, 'role' => 'admin']);
                $user->update(['current_role' => 'hr']);
            }

            // // User ke-2: spv dan user
            // if ($user->id == 2) {
            //     UserRole::create(['user_id' => $user->id, 'role' => 'spv']);
            //     UserRole::create(['user_id' => $user->id, 'role' => 'user']);
            //     $user->update(['current_role' => 'spv']);
            // }

            // // User ke-3: hanya user biasa
            // if ($user->id == 3) {
            //     UserRole::create(['user_id' => $user->id, 'role' => 'user']);
            //     $user->update(['current_role' => 'user']);
            // }
        }
    }
}
