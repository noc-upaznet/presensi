<?php

namespace App\Livewire\Navigation;

use App\Models\User;
use Livewire\Component;
use App\Models\M_Entitas;
use App\Models\M_DataKaryawan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Navbar extends Component
{
    public $entitasList = [];
    public $selectedEntitas = null;
    public $roles = [];
    public $currentRole;
    public $showRoleSwitcher = false;

    public function mount()
    {
        $this->selectedEntitas = session('selected_entitas', 'UHO');

        $user = Auth::user();

        if ($user) {
            $this->roles = $user->roles->pluck('name')->toArray();

            // Ambil hanya branch yang dimiliki user
            $this->entitasList = $user->branches()
                ->pluck('nama', 'id')
                ->toArray();
        }
    }

    public function switchRole($role)
    {
        $user = User::find(Auth::id());

        if (!$user || !$user->roles->pluck('role')->contains($role)) {
            session()->flash('error', 'Kamu tidak memiliki role tersebut.');
            return;
        }

        $user->current_role = $role;
        $user->save();

        $this->currentRole = $user->current_role;

        // Redirect full-page via Livewire (bukan SPA refresh)
        return redirect(request()->header('Referer') ?? '/');
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
