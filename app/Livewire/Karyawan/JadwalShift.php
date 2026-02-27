<?php

namespace App\Livewire\Karyawan;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\M_Jadwal;
use App\Models\M_Presensi;
use App\Models\M_DataKaryawan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Livewire\Forms\TambahDataKaryawanForm;
use App\Traits\CutoffPayrollTrait;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class JadwalShift extends Component
{
    use CutoffPayrollTrait;
    use WithPagination, WithoutUrlPagination;
    protected $paginationTheme = 'bootstrap';
    public TambahDataKaryawanForm $form;

    public $selectedTemplateId;
    public $bulan_tahun;
    public $filterBulan;
    public $kalender = [];
    public $selectedKaryawan;
    public $namaKaryawan;
    public $karyawans;
    public $users;
    public $filterJadwals = [];
    public $filterKaryawan;

    protected $listeners = ['refreshTable' => 'refresh',  'jadwalAdded' => 'onJadwalAdded', 'jadwalUpdated' => 'onJadwalUpdated'];

    public function mount()
    {
        $this->bulan_tahun = now()->format('Y-m');
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

        $user = Auth::user();
        $karyawan = M_DataKaryawan::where('user_id', $user->id)->first();
        // $jadwalId = M_Jadwal::where('bulan_tahun', $this->bulan_tahun)
        //     ->pluck('karyawan_id')
        //     ->toArray();

        if ($user->hasAnyRole('spv-teknisi|spv-helpdesk|spv-sales')) {
            $karyawan = M_DataKaryawan::where('user_id', $user->id)->first();
            $divisi = $karyawan->divisi;
            // dd($divisi);
            $entitas = $karyawan->entitas;
            // $entitas = session('selected_entitas', 'UHO');

            $this->karyawans = M_DataKaryawan::where('entitas', $entitas)
                ->where('divisi', $divisi)
                // ->whereNotIn('id', $jadwalId)
                ->orderBy('nama_karyawan')
                ->get();
            // dd($this->karyawans);
        } elseif ($user->hasRole('branch-manager')) {
            $karyawan = M_DataKaryawan::where('user_id', $user->id)->first();
            $entitasUser = M_DataKaryawan::where('user_id', $user->id)->first()->entitas;

            $this->karyawans = M_DataKaryawan::where('entitas', $entitasUser)
                // ->whereNotIn('id', $jadwalId)
                ->orderBy('nama_karyawan')
                ->get();
        } elseif ($user->hasAnyRole('admin|hr')) {
            $entitas = session('selected_entitas', 'UHO');

            $this->karyawans = M_DataKaryawan::where('entitas', $entitas)
                // ->whereNotIn('id', $jadwalId)
                ->orderBy('nama_karyawan')
                ->get();
        } elseif ($user->hasRole('user')) {
            $divisi = $karyawan->divisi;
            $entitas = $karyawan->entitas;
            $this->karyawans = M_DataKaryawan::where('entitas', $entitas)
                ->orderBy('nama_karyawan')
                ->get();
        }
        $this->applyFilters();
    }

    public function onJadwalAdded()
    {
        $this->applyFilters();
    }
    public function onJadwalUpdated()
    {
        $this->applyFilters();
    }

    public function showAdd()
    {
        $this->form->reset();
        $this->dispatch('modalTambahJadwal', action: 'show');
    }

    public function showEdit($id)
    {
        $jadwal = M_Jadwal::findOrFail(Crypt::decrypt($id));

        $this->selectedKaryawan = $jadwal->karyawan_id;
        $this->namaKaryawan = M_DataKaryawan::find($this->selectedKaryawan)?->nama_karyawan ?? '';

        $carbon = Carbon::parse($jadwal->bulan_tahun);
        $year  = $carbon->year;
        $month = $carbon->month;

        $cutoff = $this->resolveCutoff($year, $month, 'cutoff_25');

        $start = $cutoff['start']->copy();
        $end   = $cutoff['end']->copy();

        $this->bulan_tahun = $cutoff['bulanTahun'];

        $currentMonth  = $carbon->format('Y-m');
        $previousMonth = $carbon->copy()->subMonth()->format('Y-m');

        $jadwalCurrent = M_Jadwal::where('bulan_tahun', $currentMonth)
            ->where('karyawan_id', $this->selectedKaryawan)
            ->first();

        $jadwalPrevious = M_Jadwal::where('bulan_tahun', $previousMonth)
            ->where('karyawan_id', $this->selectedKaryawan)
            ->first();

        $this->kalender = [];

        $currentDate = $start->copy();

        while ($currentDate <= $end) {

            $tanggal = $currentDate->format('Y-m-d');
            $day     = $currentDate->day;

            if ($currentDate->format('Y-m') === $currentMonth) {
                $this->kalender[$tanggal] =
                    $jadwalCurrent?->{'d' . $day} ?? null;
            } else {
                $this->kalender[$tanggal] =
                    $jadwalPrevious?->{'d' . $day} ?? null;
            }

            $currentDate->addDay();
        }
        $this->dispatch('loadData', [
            'id'           => $jadwal->id,
            'bulan_tahun'  => $this->bulan_tahun,
            'karyawan_id'  => $this->selectedKaryawan,
            'kalender'     => $this->kalender,
        ])->to(ModalJadwalShift::class);

        $this->dispatch('modalEditJadwal', action: 'show');
    }

    public function showDetail($id)
    {
        $jadwal = M_Jadwal::findOrFail(Crypt::decrypt($id));

        $this->selectedKaryawan = $jadwal->karyawan_id;

        $carbon = Carbon::parse($jadwal->bulan_tahun);
        $year  = $carbon->year;
        $month = $carbon->month;

        $cutoff = $this->resolveCutoff($year, $month, 'cutoff_25');

        $startDate = $cutoff['start']->copy()->startOfDay();
        $endDate   = $cutoff['end']->copy()->startOfDay();

        $this->bulan_tahun = $cutoff['bulanTahun'];

        $currentMonth  = $carbon->format('Y-m');
        $previousMonth = $carbon->copy()->subMonth()->format('Y-m');

        $jadwalCurrent = M_Jadwal::where('bulan_tahun', $currentMonth)
            ->where('karyawan_id', $this->selectedKaryawan)
            ->first();

        $jadwalPrevious = M_Jadwal::where('bulan_tahun', $previousMonth)
            ->where('karyawan_id', $this->selectedKaryawan)
            ->first();

        $this->kalender = [];
        $izin = 0;
        $cuti = 0;

        $currentDate = $startDate->copy();

        while ($currentDate <= $endDate) {

            $tanggal = $currentDate->format('Y-m-d');
            $day     = $currentDate->day;

            if ($currentDate->format('Y-m') === $currentMonth) {
                $shiftId = $jadwalCurrent?->{'d' . $day} ?? null;
            } else {
                $shiftId = $jadwalPrevious?->{'d' . $day} ?? null;
            }

            $this->kalender[$tanggal] = $shiftId;

            if ($shiftId == 3) $izin++;
            if ($shiftId == 2) $cuti++;

            $currentDate->addDay();
        }

        $presensi = M_Presensi::where('user_id', $this->selectedKaryawan)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->get();

        $presensiHadir = [];

        foreach ($presensi as $p) {
            $tanggal = Carbon::parse($p->tanggal)->format('Y-m-d');
            $presensiHadir[$tanggal] = $p->status;
        }

        $this->dispatch('detail-data', [
            'karyawan_id' => $this->selectedKaryawan,
            'kalender' => $this->kalender,
            'presensiHadir' => $presensiHadir,
            'bulan_tahun' => $this->bulan_tahun,
            'rekap' => [
                'izin'       => $izin,
                'cuti'       => $cuti,
                'terlambat'  => $presensi->where('status', 1)->count(),
                'kehadiran'  => $presensi->count(),
            ],
        ])->to(ModalJadwalShift::class);

        $this->dispatch('modalDetailJadwal', action: 'show');
    }

    public function delete($id)
    {
        $jadwal = M_Jadwal::findOrFail(Crypt::decrypt($id));
        // dd($jadwal);
        $jadwal->delete();
        $this->dispatch('modal-confirm-delete', action: 'hide');
    }

    public function updatedFilterBulan()
    {
        $this->applyFilters();
    }

    public function filterByKaryawan($karyawanId)
    {
        $this->filterKaryawan = $karyawanId ?: null;
        $this->applyFilters();
    }

    public function applyFilters()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = M_Jadwal::with('getKaryawan');
        $user = Auth::user();
        $karyawan = M_DataKaryawan::where('user_id', $user->id)->first();
        // dd($karyawan);
        if (!empty($this->filterKaryawan)) {
            $query->where('karyawan_id', $this->filterKaryawan);
        }

        if (!empty($this->filterBulan)) {
            $query->where('bulan_tahun', 'like', $this->filterBulan . '%');
        }

        if ($user->hasAnyRole('spv-teknisi|spv-helpdesk|spv-sales')) {
            $karyawan = M_DataKaryawan::where('user_id', $user->id)->first();
            $divisi = $karyawan->divisi;
            $entitas = $karyawan->entitas;

            $query->whereHas('getKaryawan', function ($q) use ($divisi, $entitas) {
                $q->where('divisi', $divisi)
                    ->where('entitas', $entitas);
            });
        } elseif ($user->hasRole('branch-manager')) {
            $karyawan = M_DataKaryawan::where('user_id', $user->id)->first();
            $entitasUser = M_DataKaryawan::where('user_id', $user->id)->first()->entitas;

            $query->whereHas('getKaryawan', function ($q) use ($entitasUser) {
                $q
                    ->where('entitas', $entitasUser);
            });
        } elseif ($user->hasAnyRole('admin|hr')) {
            $entitas = session('selected_entitas', 'UHO');

            $query->whereHas('getKaryawan', function ($q) use ($entitas) {
                $q->where('entitas', $entitas);
            });
        } elseif ($user->hasRole('user') && $karyawan->entitas == 'MC') {
            $divisi = $karyawan->divisi;
            $entitas = $karyawan->entitas;
            $query->whereHas('getKaryawan', function ($q) use ($divisi, $entitas) {
                $q->where('divisi', $divisi)
                    ->where('entitas', $entitas);
            });
        } elseif ($user->hasRole('user') && $karyawan->entitas == 'UGR') {
            $divisi = $karyawan->divisi;
            $entitas = $karyawan->entitas;
            $query->whereHas('getKaryawan', function ($q) use ($divisi, $entitas) {
                $q->where('divisi', $divisi)
                    ->where('entitas', $entitas);
            });
        }
        $jadwals = $query->paginate(10);
        return view('livewire.karyawan.jadwal-shift', [
            'jadwals' => $jadwals,
        ]);
    }
}
