<?php

namespace App\Livewire;

use App\Models\UserRole;
use Livewire\Component;

class Roles extends Component
{
    public function render()
    {
        $roles = UserRole::with('user')->orderBy('created_at', 'desc')->latest()->paginate(10);
        return view('livewire.roles', [
            'roles' => $roles,
        ]);
    }
}
