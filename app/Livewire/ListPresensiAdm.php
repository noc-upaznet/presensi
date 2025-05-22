<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PresensiKaryawan;
use Livewire\WithPagination;

class ListPresensiAdm extends Component
{
    use WithPagination;
    public $search = '';
    protected $table = 'presensi_karyawan';

    

    public function render()
    {
        $karyawanList = PresensiKaryawan::where('nama', 'like', '%' . $this->search . '%')
            ->orderBy('tanggal', 'desc')
            ->paginate(10);

        return view('livewire.list-presensi-adm', [
            'karyawanList' => $karyawanList
        ]);
    }
}
