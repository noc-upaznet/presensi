<?php

namespace App\Livewire;

use App\Models\M_Roles;
use Livewire\Component;

class Roles extends Component
{
    public function render()
    {
        $roles = M_Roles::orderBy('created_at', 'desc')->latest()->paginate(10);
        return view('livewire.roles', [
            'roles' => $roles,
        ]);
    }
}
