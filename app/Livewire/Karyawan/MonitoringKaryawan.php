<?php

namespace App\Livewire\Karyawan;

use App\Models\M_DataKaryawan;
use App\Models\M_Presensi;
use App\Traits\CutoffPayrollTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Livewire\Component;
use Livewire\WithPagination;

class MonitoringKaryawan extends Component
{
    use CutoffPayrollTrait;
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $filterBulan = '';
    public $filterTanggal = '';
    public $filterkaryawan = '';
    public $perPage = 25;
    public $karyawanList = [];
    public $detailData = [];
    public $showDetail = false;
    public $namaKaryawan = '';
    public $sortField = 'jumlah_terlambat';
    public $sortDirection = 'desc';

    public function mount()
    {
        $today = Carbon::today();

        $year  = $today->year;
        $month = $today->month;

        if ($today->day >= 26) {
            $month++;
            if ($month > 12) {
                $month = 1;
                $year++;
            }
        }

        $this->filterBulan = sprintf('%04d-%02d', $year, $month);
    }

    public function detail($id)
    {
        try {
            $userId = Crypt::decrypt($id);

            $branch = session('selected_entitas', 'UHO');

            $karyawan = M_Presensi::query()
                ->with('getKaryawan')
                ->where('user_id', $userId)
                ->whereHas('getKaryawan', function ($q) use ($branch) {
                    $q->where('status_karyawan', '!=', 'NONAKTIF')
                        ->where('entitas', $branch);
                })
                ->first();

            if (!$karyawan) {
                return;
            }

            $this->namaKaryawan = $karyawan->getKaryawan->nama_karyawan ?? '-';

            $query = M_Presensi::query()
                ->where('user_id', $userId)
                ->where('status', 1)
                ->whereHas('getKaryawan', function ($q) use ($branch) {
                    $q->where('status_karyawan', '!=', 'NONAKTIF')
                        ->where('entitas', $branch);
                });

            if ($this->filterBulan) {
                $query->whereYear(
                    'tanggal',
                    substr($this->filterBulan, 0, 4)
                )->whereMonth(
                    'tanggal',
                    substr($this->filterBulan, 5, 2)
                );
            }

            $this->detailData = $query
                ->orderBy('tanggal', 'desc')
                ->get();

            $this->dispatch('modalDetailKeterlambatan');
        } catch (\Exception $e) {
            //
        }
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc'
                ? 'desc'
                : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        $branch = session('selected_entitas', 'UHO');

        $this->karyawanList = M_DataKaryawan::where('entitas', $branch)
            ->where('status_karyawan', '!=', 'NONAKTIF')
            ->select('id', 'nama_karyawan')
            ->get();

        $query = M_DataKaryawan::query()
            ->where('data_karyawan.entitas', $branch)
            ->where('data_karyawan.status_karyawan', '!=', 'NONAKTIF');

        /*
    |--------------------------------------------------------------------------
    | Hitung keterlambatan
    |--------------------------------------------------------------------------
    */
        $query = M_Presensi::query()
            ->with(['getKaryawan', 'getUser'])
            ->whereHas('getKaryawan', function ($q) use ($branch) {
                $q->where('status_karyawan', '!=', 'NONAKTIF')
                    ->where('entitas', $branch);
            })
            ->where('status', 1);

        if ($this->filterBulan) {
            $tanggal = Carbon::createFromFormat('Y-m', $this->filterBulan);

            $cutoff = $this->resolveCutoff(
                $tanggal->year,
                $tanggal->month,
                'cutoff_25'
            );

            $query->whereBetween('tanggal', [
                $cutoff['start'],
                $cutoff['end'],
            ]);
        }

        /*
    |--------------------------------------------------------------------------
    | Filter Karyawan
    |--------------------------------------------------------------------------
    */
        if ($this->filterkaryawan) {
            $query->where('data_karyawan.id', $this->filterkaryawan);
        }

        /*
    |--------------------------------------------------------------------------
    | Search
    |--------------------------------------------------------------------------
    */
        if ($this->search) {
            $query->where(
                'data_karyawan.nama_karyawan',
                'like',
                '%' . $this->search . '%'
            );
        }

        /*
    |--------------------------------------------------------------------------
    | Select
    |--------------------------------------------------------------------------
    */
        $query->select(
            'data_karyawan.id',
            'data_karyawan.nama_karyawan'
        )
            ->selectRaw('COUNT(presensi.id) as jumlah_terlambat')
            ->groupBy(
                'data_karyawan.id',
                'data_karyawan.nama_karyawan'
            );

        /*
    |--------------------------------------------------------------------------
    | Sorting
    |--------------------------------------------------------------------------
    */
        if ($this->sortField === 'nama_karyawan') {
            $query->orderBy(
                'data_karyawan.nama_karyawan',
                $this->sortDirection
            );
        } else {
            $query->orderBy(
                'jumlah_terlambat',
                $this->sortDirection
            );
        }

        $datas = $query->paginate($this->perPage);

        return view('livewire.karyawan.monitoring-karyawan', [
            'datas' => $datas,
        ]);
    }
}
