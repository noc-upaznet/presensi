<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\RoleLokasi as RoleLokasiModel;

class RoleLokasi extends Component
{
    use WithPagination;

    public $search = '';
    public $lock = false;
    public $lokasi_presensi;
    public $lokasi_list;


    public function confirmHapusLokasi($id)
    {
        $this->lokasi_presensi = $id;
    }

    public function deleteLokasi()
    {
        RoleLokasiModel::find($this->lokasi_presensi)->delete();
        $this->lokasi_list = RoleLokasiModel::all();

        // Notifikasi
        $this->dispatch('lokasiTerhapus');

        // Reset data setelah penghapusan
        $this->lokasi_presensi = null;
    }

    public function mount()
    {
        // Load lokasi saat komponen pertama kali di-mount
        $this->lokasi_list = RoleLokasiModel::all();
    }

    public function render()
    {
        $lokasiList = RoleLokasiModel::where('nama_karyawan', 'like', '%' . $this->search . '%')->paginate(10);

        return view('livewire.role-lokasi', [
            'lokasiList' => $lokasiList,
        ]);
    }
}
