<?php

namespace App\Livewire;

use Livewire\Component;

class RoleUsers extends Component
{
    Public $listeners = ['refreshTable' => '$refresh'];

    // public string $activeTab = '#pills-jabatan'; // default

    // public function setActiveTab($tab)
    // {
    //     $this->activeTab = $tab;
    // }

    public $tab = 'pills-user';

    public function setTab($tab)
    {
        $this->tab = $tab;
    }
    
    public function render()
    {
        return view('livewire.role-users');
    }
}
