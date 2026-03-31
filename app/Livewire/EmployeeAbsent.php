<?php

namespace App\Livewire;

use App\Models\M_DataKaryawan;
use App\Models\M_Divisi;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class EmployeeAbsent extends Component
{
    public $perPage = 25;
    use WithPagination, WithoutUrlPagination;
    protected $paginationTheme = 'bootstrap';

    public $filterDivisi;
    public $divisiList;
    public $search;
    public $mode = 'all';

    public function mount()
    {
        $this->divisiList = M_Divisi::all();
    }

    public function render()
    {
        $entitas = session('selected_entitas', 'UHO');

        $query = M_DataKaryawan::query()
            ->where('status_karyawan', '!=', 'NONAKTIF')
            ->where('entitas', $entitas);

        // filter
        if ($this->filterDivisi) {
            $query->where('divisi', $this->filterDivisi);
        }

        if ($this->search) {
            $query->where('nama_karyawan', 'like', '%' . $this->search . '%');
        }

        // tidak presensi hari ini
        $query->whereNotIn('id', function ($q) {
            $q->select('user_id')
                ->from('presensi')
                ->where('deleted_at', null)
                ->whereDate('tanggal', now());
        });

        // MODE 2: harus ada pengajuan
        if ($this->mode === 'pengajuan') {
            $query->whereIn('id', function ($q) {
                $q->select('karyawan_id')
                    ->from('pengajuan')
                    ->where('status', 1)
                    ->where('deleted_at', null)
                    ->whereDate('tanggal', now());
            });
        }

        $datas = $query->paginate($this->perPage);

        return view('livewire.employee-absent', compact('datas'));
    }
}
