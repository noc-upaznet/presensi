<?php

namespace App\Livewire;

use App\Models\M_DataKaryawan;
use App\Models\M_Divisi;
use App\Models\M_Pengajuan;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class EmployeeAbsent extends Component
{
    public $perPage = 10;
    use WithPagination, WithoutUrlPagination;
    protected $paginationTheme = 'bootstrap';

    public $filterDivisi;
    public $divisiList;
    public $search;

    public function mount()
    {
        $this->divisiList = M_Divisi::all();
    }

    public function render()
    {
        $query = M_Pengajuan::with(['getKaryawan', 'getShift']);
        $entitas = session('selected_entitas', 'UHO');

        $karyawanIdList = M_DataKaryawan::where('entitas', $entitas)->pluck('id');

        if ($this->filterDivisi) {
            $karyawanIdList = M_DataKaryawan::where('entitas', $entitas)
                ->where('divisi', $this->filterDivisi)
                ->pluck('id');
        }

        if ($this->search) {
            $karyawanIdList = M_DataKaryawan::where('entitas', $entitas)
                ->where('nama_karyawan', 'like', '%' . $this->search . '%')
                ->pluck('id');
        }

        $datas = $query->whereIn('karyawan_id', $karyawanIdList)
            ->where('status', 1)
            ->whereDate('tanggal', now())
            ->orderBy('tanggal', 'desc')
            ->paginate($this->perPage);

        return view('livewire.employee-absent', [
            'datas' => $datas,
        ]);
    }
}
