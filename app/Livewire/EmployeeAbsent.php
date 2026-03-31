<?php

namespace App\Livewire;

use App\Models\M_DataKaryawan;
use App\Models\M_Divisi;
use App\Models\M_Pengajuan;
use App\Models\M_Presensi;
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

        // 🔹 filter karyawan
        $karyawanQuery = M_DataKaryawan::where('entitas', $entitas);

        if ($this->filterDivisi) {
            $karyawanQuery->where('divisi', $this->filterDivisi);
        }

        if ($this->search) {
            $karyawanQuery->where('nama_karyawan', 'like', '%' . $this->search . '%');
        }

        $karyawanIdList = $karyawanQuery->pluck('id');

        // ambil yang sudah presensi hari ini
        $presensiHariIni = M_Presensi::whereDate('tanggal', now())
            ->pluck('user_id');

        // query utama
        $datas = $query->whereIn('karyawan_id', $karyawanIdList)
            ->where('status', 1)
            ->whereDate('tanggal', now())

            // tidak ada di presensi
            ->whereNotIn('karyawan_id', $presensiHariIni)

            ->orderBy('tanggal', 'desc')
            ->paginate($this->perPage);

        return view('livewire.employee-absent', [
            'datas' => $datas,
        ]);
    }
}
