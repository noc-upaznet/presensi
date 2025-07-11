<?php

namespace App\Livewire\Navigation;

use Livewire\Component;
use App\Models\M_Entitas;
use Illuminate\Support\Facades\Session;

class Navbar extends Component
{
    public $entitasList = [];
    public $selectedEntitas = null;

    public function mount()
    {
        // Default ke 'UHO' jika belum ada entitas yang dipilih
        $this->selectedEntitas = Session::get('selected_entitas', 'UHO');

        // Ambil semua entitas dari DB
        $this->entitasList = M_Entitas::all();
        // Tambahkan manual opsi 'All Branch' ke dropdown
        $this->entitasList = M_Entitas::all()
        ->pluck('nama') // ambil hanya nama
        ->values(); // reset indeks
    }

    public function selectEntitas($entitas)
    {
        $this->selectedEntitas = $entitas;
        Session::put('selected_entitas', $entitas);

        // Redirect ke halaman sebelumnya atau home
        return redirect(request()->header('Referer') ?? '/');
    }

    public function render()
    {
        return view('livewire.navigation.navbar');
    }
}
