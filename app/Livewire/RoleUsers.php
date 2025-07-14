<?php

namespace App\Livewire;

use Livewire\Component;

class RoleUsers extends Component
{
    Public $listeners = ['refreshTable' => '$refresh'];

    public $tab = 'pills-jabatan';

    public function setTab($tab)
    {
        $this->tab = $tab;
    }
    
    public function render()
    {
        return view('livewire.role-users');
    }
}
