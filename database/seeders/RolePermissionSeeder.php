<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Buat permission
        $edit = Permission::create(['name' => 'edit user']);
        $delete = Permission::create(['name' => 'delete user']);
        $approve = Permission::create(['name' => 'approve user']);

        // Buat role dan berikan permission
        $admin = Role::create(['name' => 'admin']);
        $hr = Role::create(['name' => 'hr']);
        $spv = Role::create(['name' => 'spv']);
        
        $admin->givePermissionTo([$edit, $delete]);
        $hr->givePermissionTo([$edit, $approve]);
        $spv->givePermissionTo([$edit, $approve]);

        // Assign role ke user pertama (jika ada)
        $user = User::first();
        if ($user) {
            $user->assignRole('admin');
        }
    }
}
