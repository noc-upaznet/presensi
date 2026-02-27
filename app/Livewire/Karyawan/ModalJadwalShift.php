<?php

namespace App\Livewire\Karyawan;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use App\Models\M_Jadwal;
use App\Models\M_Presensi;
use App\Models\M_JadwalShift;
use App\Models\M_DataKaryawan;
use App\Models\M_TemplateWeek;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Services\IndonesiaHolidayService;
use App\Traits\CutoffPayrollTrait;
use Livewire\Attributes\On;

class ModalJadwalShift extends Component
{
    use CutoffPayrollTrait;
    public $holidays = [];
    public $karyawans;
    public $users;
    public $jadwalShifts;
    public $templateWeeks;
    public $bulan;
    public $bulan_tahun;
    public $selectedKaryawan;
    public $jadwal_id;
    public $namaKaryawan;

    public $selectedTemplateId;
    public $kalender = []; // format: ['minggu' => '', 'senin' => '', dst]
    public $kalenderVersion = 0;
    public $rekap = [];
    public $presensiHadir = [];

    // public $userId;

    protected $listeners = ['setKaryawan', 'bulanChanged' => 'setBulan'];

    public function getBulanTahunProperty()
    {
        $carbon = Carbon::parse($this->bulan_tahun);

        return [
            'bulan' => (int) $carbon->format('m'),
            'tahun' => (int) $carbon->format('Y'),
        ];
    }

    public function setBulan($value)
    {
        $this->bulan = $value;
    }

    public function updatedBulanTahun(IndonesiaHolidayService $holidayService)
    {
        $user = Auth::user();

        // Ambil ID karyawan yang sudah punya jadwal di bulan-tahun tersebut
        $jadwalId = M_Jadwal::where('bulan_tahun', $this->bulan_tahun)
            ->pluck('karyawan_id')
            ->toArray();
        // dd($jadwalId);

        if ($user->hasAnyRole('spv-teknisi|spv-helpdesk|spv-sales')) {
            $karyawan = M_DataKaryawan::where('user_id', $user->id)->first();
            $divisi = $karyawan->divisi;
            $entitas = $karyawan->entitas;
            $this->karyawans = M_DataKaryawan::where('divisi', $divisi)
                ->where('entitas', $entitas)
                ->whereNotIn('id', $jadwalId)
                ->orderBy('nama_karyawan')
                ->get();
        } elseif ($user->hasAnyRole('admin|hr')) {
            $entitas = session('selected_entitas', 'UHO');
            $this->karyawans = M_DataKaryawan::where('entitas', $entitas)
                ->whereNotIn('id', $jadwalId)
                ->orderBy('nama_karyawan')
                ->get();
        } elseif ($user->hasRole('branch-manager')) {
            $karyawan = M_DataKaryawan::where('user_id', $user->id)->first();
            $entitasUser = M_DataKaryawan::where('user_id', $user->id)->first()->entitas;
            $this->karyawans = M_DataKaryawan::where('entitas', $entitasUser)
                ->whereNotIn('id', $jadwalId)
                ->orderBy('nama_karyawan')
                ->get();
        }
        $this->holidays = $holidayService->getHolidaysByMonth($this->bulan_tahun);
        $this->selectedKaryawan = null;
    }


    public function fillCalendarFromTemplate()
    {
        if (empty($this->selectedTemplateId)) {
            $this->kalender = [];
            return;
        }

        $template = M_TemplateWeek::find($this->selectedTemplateId);
        if (!$template) return;

        if (!$this->bulan_tahun) {
            $this->bulan_tahun = now()->format('Y-m');
        }

        $carbon = Carbon::parse($this->bulan_tahun);
        $year  = $carbon->year;
        $month = $carbon->month;

        $cutoff = $this->resolveCutoff($year, $month, 'cutoff_25');

        $startDate = $cutoff['start']->copy()->startOfDay();
        $endDate   = $cutoff['end']->copy()->startOfDay();

        $this->kalender = [];

        $currentDate = $startDate->copy();

        while ($currentDate <= $endDate) {

            $tanggalFull = $currentDate->format('Y-m-d');
            $namaHari = strtolower($currentDate->locale('id')->isoFormat('dddd'));

            // pastikan nama kolom template cocok
            if (isset($template->{$namaHari})) {
                $this->kalender[$tanggalFull] = $template->{$namaHari};
            } else {
                $this->kalender[$tanggalFull] = null;
            }

            $currentDate->addDay();
        }

        $this->kalenderVersion++;
    }

    public function store()
    {
        if (!$this->bulan_tahun || !$this->selectedKaryawan) return;

        $carbon = Carbon::parse($this->bulan_tahun);
        $year  = $carbon->year;
        $month = $carbon->month;

        $cutoff = $this->resolveCutoff($year, $month, 'cutoff_25');

        $startDate = $cutoff['start']->copy()->startOfDay();
        $endDate   = $cutoff['end']->copy()->startOfDay();

        $currentMonth  = $carbon->format('Y-m');
        $previousMonth = $carbon->copy()->subMonth()->format('Y-m');

        $dataCurrent  = [];
        $dataPrevious = [];

        $currentDate = $startDate->copy();

        while ($currentDate <= $endDate) {

            $tanggal = $currentDate->format('Y-m-d');
            $day     = $currentDate->day;
            $shift   = $this->kalender[$tanggal] ?? null;

            if ($currentDate->format('Y-m') === $currentMonth) {
                $dataCurrent["d{$day}"] = $shift;
            } else {
                $dataPrevious["d{$day}"] = $shift;
            }

            $currentDate->addDay();
        }

        // Simpan / update bulan sekarang
        M_Jadwal::updateOrCreate(
            [
                'bulan_tahun' => $currentMonth,
                'karyawan_id' => $this->selectedKaryawan,
            ],
            $dataCurrent
        );

        // Simpan / update bulan sebelumnya
        M_Jadwal::updateOrCreate(
            [
                'bulan_tahun' => $previousMonth,
                'karyawan_id' => $this->selectedKaryawan,
            ],
            $dataPrevious
        );

        // Reset
        $this->reset(['bulan_tahun', 'selectedKaryawan', 'kalender', 'selectedTemplateId']);

        $this->dispatch('swal', params: [
            'title' => 'Data Saved',
            'icon' => 'success',
            'text' => 'Data has been saved successfully'
        ]);

        $this->dispatch('modalTambahJadwal', action: 'hide');
        $this->dispatch('refresh');
        $this->dispatch('jadwalAdded');
    }

    #[On('loadData')]
    public function loadData($data)
    {
        $this->jadwal_id        = $data['id'] ?? null;
        $this->bulan_tahun      = $data['bulan_tahun'] ?? null;
        $this->selectedKaryawan = $data['karyawan_id'] ?? null;
        $this->namaKaryawan     = M_DataKaryawan::find($this->selectedKaryawan)?->nama_karyawan ?? '';
        $this->kalender         = $data['kalender'] ?? [];
    }

    #[On('detail-data')]
    public function loadDetail($data)
    {
        $this->presensiHadir = $data['presensiHadir'] ?? [];
        $this->rekap         = $data['rekap'] ?? [];
        $this->jadwal_id     = $data['id'] ?? null;
        $this->bulan_tahun   = $data['bulan_tahun'] ?? null;
        $this->selectedKaryawan = $data['karyawan_id'] ?? null;
        $this->namaKaryawan = $this->selectedKaryawan
            ? M_DataKaryawan::find($this->selectedKaryawan)?->nama_karyawan
            : null;
        // dd($this->bulan_tahun);

        $this->kalender = $data['kalender'] ?? [];
        // dd($this->kalender);
        $this->dispatch('$refresh');
    }

    public function loadRekap($id)
    {
        $presensi = M_Presensi::where('karyawan_id', $id)
            ->whereMonth('tanggal', now()->month)
            ->get();
        $this->rekap['izin'] = $presensi->where('status', 3)->count();
        $this->rekap['cuti'] = $presensi->where('status', 2)->count();
        $this->rekap['terlambat'] = $presensi->where('status', 1)->count();
        $this->rekap['kehadiran'] = $presensi->where('status', '!=', '')->count();
    }

    public function saveEdit()
    {
        if (!$this->bulan_tahun || !$this->selectedKaryawan) return;

        $carbon = Carbon::parse($this->bulan_tahun);
        $year  = $carbon->year;
        $month = $carbon->month;

        $cutoff = $this->resolveCutoff($year, $month, 'cutoff_25');

        $startDate = $cutoff['start']->copy()->startOfDay();
        $endDate   = $cutoff['end']->copy()->startOfDay();

        $currentMonth  = $carbon->format('Y-m');
        $previousMonth = $carbon->copy()->subMonth()->format('Y-m');

        $dataCurrent  = [];
        $dataPrevious = [];

        $currentDate = $startDate->copy();

        while ($currentDate <= $endDate) {

            $tanggal = $currentDate->format('Y-m-d');
            $day     = $currentDate->day;
            $shift   = $this->kalender[$tanggal] ?? null;

            if ($currentDate->format('Y-m') === $currentMonth) {
                $dataCurrent["d{$day}"] = $shift;
            } else {
                $dataPrevious["d{$day}"] = $shift;
            }

            $currentDate->addDay();
        }

        // Update bulan sekarang
        M_Jadwal::updateOrCreate(
            [
                'bulan_tahun' => $currentMonth,
                'karyawan_id' => $this->selectedKaryawan,
            ],
            $dataCurrent
        );

        // Update bulan sebelumnya
        M_Jadwal::updateOrCreate(
            [
                'bulan_tahun' => $previousMonth,
                'karyawan_id' => $this->selectedKaryawan,
            ],
            $dataPrevious
        );

        $this->dispatch('swal', params: [
            'title' => 'Data Updated',
            'icon' => 'success',
            'text' => 'Data has been updated successfully'
        ]);

        $this->dispatch('modalEditJadwal', action: 'hide');
        $this->dispatch('refresh');
        $this->dispatch('jdawalUpdated');
    }

    public function delete($id)
    {
        // dd($id);
        M_Jadwal::find(Crypt::decrypt($id))->delete();
        $this->dispatch('swal', params: [
            'title' => 'Data Deleted',
            'icon' => 'success',
            'text' => 'Data has been deleted successfully'
        ]);
        $this->dispatch('modal-confirm-delete', action: 'show');
        $this->dispatch('refresh');
    }

    public function mount(IndonesiaHolidayService $holidayService)
    {
        $this->bulan_tahun = now()->format('Y-m');
        $user = Auth::user();
        $karyawan = M_DataKaryawan::where('user_id', $user->id)->first();
        $jadwalId = M_Jadwal::where('bulan_tahun', $this->bulan_tahun)
            ->pluck('karyawan_id')
            ->toArray();
        if ($user->hasAnyRole('spv-teknisi|spv-helpdesk|spv-sales')) {
            $divisi = $karyawan->divisi;
            $entitas = $karyawan->entitas;

            $this->karyawans = M_DataKaryawan::where('divisi', $divisi)
                ->where('entitas', $entitas)
                ->whereNotIn('id', $jadwalId) // filter
                ->orderBy('nama_karyawan')
                ->get();
        } elseif ($user->hasRole('branch-manager')) {
            $karyawan = M_DataKaryawan::where('user_id', $user->id)->first();
            $entitasUser = M_DataKaryawan::where('user_id', $user->id)->first()->entitas;
            $this->karyawans = M_DataKaryawan::where('entitas', $entitasUser)
                ->whereNotIn('id', $jadwalId)
                ->orderBy('nama_karyawan')
                ->get();
        } elseif ($user->hasAnyRole('admin|hr')) {
            $entitas = session('selected_entitas', 'UHO');

            $this->karyawans = M_DataKaryawan::where('entitas', $entitas)
                ->whereNotIn('id', $jadwalId)
                ->orderBy('nama_karyawan')
                ->get();
        } elseif ($user->hasRole('user')) {
            $entitas = $karyawan->entitas;
            $this->karyawans = M_DataKaryawan::where('entitas', $entitas)
                ->orderBy('nama_karyawan')
                ->get();
        }

        $this->holidays = $holidayService->getHolidaysByMonth($this->bulan_tahun);

        $this->jadwalShifts = M_JadwalShift::orderBy('nama_shift')->get();
        $this->templateWeeks = M_TemplateWeek::orderBy('nama_template')->get();
    }

    public function render()
    {
        if (empty($this->kalender)) {
            return view('livewire.karyawan.modal-jadwal-shift', [
                'jumlahBaris' => 0,
                'hariPertama' => 0,
                'totalHari'   => 0,
                'startDate'   => null,
                'endDate'     => null,
            ]);
        }

        $dates = collect(array_keys($this->kalender))->sort()->values();

        $startDate = \Carbon\Carbon::parse($dates->first());
        $endDate   = \Carbon\Carbon::parse($dates->last());

        $totalHari = $dates->count();
        $hariPertama = $startDate->dayOfWeek;
        $totalCell   = $hariPertama + $totalHari;
        $jumlahBaris = ceil($totalCell / 7);

        return view('livewire.karyawan.modal-jadwal-shift', [
            'jumlahBaris' => $jumlahBaris,
            'hariPertama' => $hariPertama,
            'totalHari'   => $totalHari,
            'startDate'   => $startDate,
            'endDate'     => $endDate,
        ]);
    }
}
