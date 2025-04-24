<?php

namespace App\Livewire;

use Livewire\Component;

class ClockIn extends Component
{
    // public function mount()
    // {
    //     $this->userName = auth()->user()->name ?? 'Nadia Safira Khairunnisa';
    // }

    public function clockIn()
    {
        // Simpan ke DB (logika clock-in)
        session()->flash('message', 'Clocked In!');
    }

    public function clockOut()
    {
        // Simpan ke DB (logika clock-out)
        session()->flash('message', 'Clocked Out!');
    }
    public function render()
    {
        return view('livewire.clock-in');
    }
}
