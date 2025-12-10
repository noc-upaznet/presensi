<?php

namespace App\Livewire;

use Livewire\Attributes\Url;
use Livewire\Component;

class DataMaster extends Component
{
    public $listeners = ['refreshTable' => '$refresh'];

    #[Url(as: 'tab')]
    public $tab = 'pills-jabatan';

    public function setTab($tab)
    {
        $this->tab = $tab;
    }

    public function render()
    {
        return view('livewire.data-master');
    }
}
