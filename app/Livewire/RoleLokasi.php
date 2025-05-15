<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\RoleLokasi as RoleLokasiModel;

class RoleLokasi extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        $lokasiList = RoleLokasiModel::where('nama_karyawan', 'like', '%' . $this->search . '%')->paginate(10);

        return view('livewire.role-lokasi', [
            'lokasiList' => $lokasiList,
        ]);
    }
}
