<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\RoleLokasi as RoleLokasiModel;
use Google\Service\Directory\Role;

class RoleLokasi extends Component
{
    use WithPagination;

    public $locked = false;
    public $search = '';
    public $lock = false;
    public $lokasi_presensi;
    public $lokasi_list;

    public function mount()
    {
        $this->lokasi_list = RoleLokasiModel::all();
    }

    public function simpanRole()
    {
        RoleLokasiModel::create([
            'nama_karyawan' => $this->nama_karyawan,
            'koordinat' => $this->koordinat,
            'alamat' => $this->alamat,
            'status' => $this->status,
        ]);

        // Reset input
        $this->reset(['nama_karyawan', 'koordinat', 'alamat', 'status']);

        // Refresh data lokasi
        $this->lokasi_list = RoleLokasiModel::all();

        // Kirim notifikasi (opsional)
        session()->flash('message', 'RolecLokasi berhasil ditambahkan.');
        $this->dispatch('roleLokasiAdded');
    }

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

    public function render()
    {
        $lokasiList = RoleLokasiModel::where('nama_karyawan', 'like', '%' . $this->search . '%')
            ->paginate(10);

        return view('livewire.role-lokasi', [
            'lokasiList' => $lokasiList,
        ]);
    }
}
