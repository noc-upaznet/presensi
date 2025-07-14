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
        // Default ke 'UHO' jika belum ada entitas yang dipilih
        $this->selectedEntitas = Session::get('selected_entitas', 'UHO');

        // Ambil semua entitas dari DB
        $this->entitasList = M_Entitas::all();
        // Tambahkan manual opsi 'All Branch' ke dropdown
        $this->entitasList = M_Entitas::all()->pluck('nama')->values();

        $user = Auth::user();
        if ($user) {
            $this->roles = $user->roles->pluck('role')->toArray();
            $this->currentRole = $user->current_role;

            // Ambil data karyawan untuk cek jabatan
            $dataKaryawan = M_DataKaryawan::where('user_id', $user->id)->first();

            // Logika menampilkan role switcher
            $this->showRoleSwitcher =
                // Punya role HR dan SPV
                (in_array('hr', $this->roles) && in_array('spv', $this->roles)) ||

                // Atau role SPV dan jabatan di karyawan = HR
                (in_array('spv', $this->roles) && $dataKaryawan && strtolower($dataKaryawan->jabatan) === 'hr');
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
