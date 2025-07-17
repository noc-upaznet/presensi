<?php

namespace App\Livewire\ModalRole;

use App\Models\User;
use App\Models\UserRole;
use Livewire\Component;

class ModalRoles extends Component
{
    public $nama;
    public $users;
    public $role;
    public $user_id;
    public $listeners = ['refreshTable' => 'refresh'];

    public function mount()
    {
        $this->users = User::all();
    }
    public function store()
    {
        $this->validate([
            'user_id' => 'required',
            'role' => 'required',
        ]);

        $data = [
            'user_id' => $this->user_id,
            'role' => $this->role,
        ];
        // dd($data);
        UserRole::create($data);

        $this->reset(['user_id', 'role']);

        $this->dispatch('swal', params: [
            'title' => 'Data Saved',
            'icon' => 'success',
            'text' => 'Data has been Saved successfully'
        ]);

        $this->dispatch('refresh');
        $this->dispatch('modalAddRoles', action: 'hide');
    }

    public function render()
    {
        return view('livewire.modal-role.modal-roles');
    }
}
