<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole('admin');

        $hr = User::create([
            'name' => 'HR',
            'email' => 'hr@gmail.com',
            'password' => bcrypt('password'),
        ]);
        $hr->assignRole('hr');

        $spv = User::create([
            'name' => 'Supervisor',
            'email' => 'spv@gmail.com',
            'password' => bcrypt('password'),
        ]);
        $spv->assignRole('spv');
    }
}
