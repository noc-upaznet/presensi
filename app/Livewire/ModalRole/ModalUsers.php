<?php

namespace App\Livewire\ModalRole;

use App\Models\User;
use App\Models\M_Roles;
use Livewire\Component;
use App\Models\M_Entitas;

class ModalUsers extends Component
{
    public $branches;
    public $selectedBranch = null;
    public $selectedRoles = [];
    public $roles;
    public $name;
    public $email;
    public $password;
    public $confirm_password;

    public function mount()
    {
        $this->roles = M_Roles::all();
        $this->selectedRoles = [];  
    }

    public function storeUsers()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'selectedRoles' => 'required|array|min:1',
        ]);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'password' => bcrypt($this->password),
            'roles' => json_encode($this->selectedRoles),
        ];
        dd($data);

        User::create($data);

        // Assuming you have a model to handle the user roles
        // M_UserRole::create($data);

        $this->dispatch('swal', [
            'title' => 'Data Saved',
            'icon' => 'success',
            'text' => 'User roles have been saved successfully'
        ]);

        // Reset form
        $this->reset(['selectedBranch', 'selectedRoles']);
    }

    public function render()
    {
        return view('livewire.modal-role.modal-users');
    }
}
