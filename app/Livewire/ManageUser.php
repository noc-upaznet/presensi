<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\Attributes\Title;

class ManageUser extends Component
{
    #[Title('Manage-user')]

    #[Url(as: 'tab')]
    public $tab = 'users';

    public function setTab($tab)
    {
        $this->tab = $tab;
    }
    public function render()
    {
        return view('livewire.manage-user');
    }
}
