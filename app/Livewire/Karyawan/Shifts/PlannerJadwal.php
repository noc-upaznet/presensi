<?php

namespace App\Livewire\Karyawan\Shifts;

use App\Exports\Planner\GuideExport;
use App\Exports\Planner\PlannerJadwalExport;
use App\Models\M_DataKaryawan;
use App\Models\M_Jadwal;
use App\Models\M_JadwalShift;
use App\Models\M_TemplateWeek;
use App\Traits\CutoffPayrollTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class PlannerJadwal extends Component
{

    use CutoffPayrollTrait, WithFileUploads;

    public $file;

    public $bulan;
    public $selectedEntitas;

    public $templates = [];

    public $karyawan = [];

    public $shift = [];

    public $planner = [];

    public $calendar = [];
    public $search = '';

    public function mount()
    {
        $this->bulan = now()->format('Y-m');
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

        $this->bulan = sprintf('%04d-%02d', $year, $month);

        $this->templates = M_TemplateWeek::orderBy('nama_template')->get();

        $this->shift = M_JadwalShift::orderBy('id')->get();

        $this->loadKaryawan();

        $this->loadCalendar();

        $this->loadPlanner();
    }

    private function loadKaryawan(): void
    {
        $user = Auth::user();

        $selectedEntitas = session('selected_entitas', 'UHO');

        $karyawanLogin = M_DataKaryawan::where('user_id', $user->id)->first();

        $query = M_DataKaryawan::query()
            ->where('status_karyawan', '!=', 'NONAKTIF');


        if ($user->hasAnyRole(['spv-teknisi', 'spv-helpdesk'])) {

            $query->where('divisi', $karyawanLogin->divisi);

            if ($karyawanLogin->entitas == 'UNR') {

                $query->whereIn('entitas', ['UNR', 'UHO']);
            } else {

                $query->where('entitas', $karyawanLogin->entitas);
            }
        } elseif ($user->hasRole('spv-sales')) {

            $query->whereIn('divisi', [
                $karyawanLogin->divisi,
                'Support'
            ])
                ->whereIn('jabatan', [
                    $karyawanLogin->jabatan,
                    'GO'
                ]);

            if ($karyawanLogin->entitas == 'UNB') {

                $query->whereIn('entitas', ['UNB', 'UHO']);
            } else {

                $query->where('entitas', $karyawanLogin->entitas);
            }
        } elseif ($user->hasRole('branch-manager')) {

            $query->where('entitas', $karyawanLogin->entitas);
        } elseif ($user->hasAnyRole(['admin', 'hr'])) {

            $query->where('entitas', $selectedEntitas);
        } else {

            $query->where('entitas', $karyawanLogin->entitas);
        }

        $this->karyawan = $query
            ->orderBy('planner_order')
            ->orderBy('nama_karyawan')
            ->get();
    }

    public function updateShift(int $karyawanId, string $bulan, string $kolom, $shiftId): void
    {

        if ($shiftId === '') {
            $shiftId = null;
        }

        if (!preg_match('/^d([1-9]|[12][0-9]|3[01])$/', $kolom)) {
            return;
        }

        $jadwal = M_Jadwal::firstOrCreate([
            'bulan_tahun' => $bulan,
            'karyawan_id' => $karyawanId,
        ]);

        $jadwal->{$kolom} = $shiftId;
        $jadwal->save();

        // Update planner agar tampilan langsung berubah
        $this->planner[$karyawanId][$kolom] = $shiftId;
    }

    public function loadPlanner()
    {
        $this->planner = [];

        $bulanList = collect($this->calendar)
            ->pluck('ym')
            ->unique()
            ->values();

        $karyawanIds = $this->karyawan
            ->pluck('id')
            ->values();

        // Ambil semua jadwal hanya sekali query
        $jadwal = M_Jadwal::whereIn('bulan_tahun', $bulanList)
            ->whereIn('karyawan_id', $karyawanIds)
            ->get()
            ->keyBy(function ($item) {
                return $item->karyawan_id . '_' . $item->bulan_tahun;
            });

        foreach ($this->karyawan as $pegawai) {

            foreach ($this->calendar as $tanggal) {

                $key = $pegawai->id . '_' . $tanggal['ym'];

                $row = $jadwal->get($key);

                $this->planner[$pegawai->id][$tanggal['column']] =
                    $row?->{$tanggal['column']};
            }
        }
    }

    public function loadCalendar()
    {
        $carbon = Carbon::parse($this->bulan);

        $cutoff = $this->resolveCutoff(
            $carbon->year,
            $carbon->month,
            'cutoff_25'
        );

        $this->calendar = [];

        $tanggal = $cutoff['start']->copy();

        $week = 1;

        while ($tanggal <= $cutoff['end']) {

            $isLastWeekDay =
                $tanggal->copy()->addDay()->gt($cutoff['end']) || // hari terakhir kalender
                $tanggal->dayOfWeek == Carbon::SATURDAY;          // akhir minggu

            $this->calendar[] = [
                'date'      => $tanggal->copy(),
                'day'       => $tanggal->day,
                'month'     => $tanggal->translatedFormat('M'),
                'column'    => 'd' . $tanggal->day,
                'ym'        => $tanggal->format('Y-m'),
                'week'      => $week,
                'is_divider' => $isLastWeekDay,
            ];

            if ($tanggal->dayOfWeek == Carbon::SATURDAY) {
                $week++;
            }

            $tanggal->addDay();
        }
    }

    public function updatedSelectedEntitas()
    {
        $this->loadKaryawan();
        $this->loadPlanner();
    }

    public function updatedBulan()
    {

        $this->loadCalendar();
        $this->loadPlanner();
    }

    public function updateOrder($rows)
    {
        foreach ($rows as $row) {

            M_DataKaryawan::where('id', $row['id'])
                ->update([
                    'planner_order' => $row['order']
                ]);
        }

        $this->loadKaryawan();
    }

    public function importExcel()
    {
        // Ambil file temporary dari S3
        $stream = Storage::disk('s3')->readStream(
            $this->file->getPathname()
        );

        $temp = storage_path('app/temp/import.xlsx');

        if (!is_dir(dirname($temp))) {
            mkdir(dirname($temp), 0777, true);
        }

        $target = fopen($temp, 'w');

        stream_copy_to_stream($stream, $target);

        fclose($stream);
        fclose($target);

        // Baca excel
        $sheets = Excel::toArray([], $temp);

        // Hapus file temporary
        unlink($temp);

        // Ambil sheet pertama
        $rows = $sheets[0];

        /*
    |--------------------------------------------------------------------------
    | Template
    | A = No
    | B = Nama Karyawan
    | C dst = Jadwal
    |--------------------------------------------------------------------------
    */

        // Header tanggal
        array_shift($rows);

        // Header hari
        array_shift($rows);

        foreach ($rows as $row) {

            if (!isset($row[1]) || trim($row[1]) === '') {
                continue;
            }

            $pegawai = M_DataKaryawan::where(
                'nama_karyawan',
                trim($row[1])
            )->first();

            if (!$pegawai) {
                continue;
            }

            foreach ($this->calendar as $index => $tanggal) {

                $kodeShift = strtoupper(trim((string) ($row[$index + 2] ?? '')));

                if ($kodeShift === '') {
                    continue;
                }

                // Excel menghilangkan angka 0 di depan
                if (ctype_digit($kodeShift) && strlen($kodeShift) < 4) {
                    $kodeShift = str_pad($kodeShift, 4, '0', STR_PAD_LEFT);
                }

                $shift = M_JadwalShift::whereRaw(
                    'UPPER(kode_shift) = ?',
                    [$kodeShift]
                )->first();

                if (!$shift) {
                    continue;
                }

                $jadwal = M_Jadwal::firstOrCreate([
                    'bulan_tahun' => $tanggal['ym'],
                    'karyawan_id' => $pegawai->id,
                ]);

                $jadwal->{$tanggal['column']} = $shift->id;
                $jadwal->save();

                // Update tampilan Livewire
                $this->planner[$pegawai->id][$tanggal['column']] = $shift->id;
            }
        }

        $this->loadPlanner();

        $this->reset('file');

        $this->dispatch('swal', params: [
            'title' => 'Import Berhasil',
            'icon' => 'success',
            'text' => 'Jadwal berhasil diimport.'
        ]);
    }

    public function exportTemplate()
    {
        return Excel::download(
            new PlannerJadwalExport($this->bulan),
            'Template Planner.xlsx'
        );
    }

    public function exportGuide()
    {
        return Excel::download(
            new GuideExport(),
            'Panduan Import Jadwal.xlsx'
        );
    }

    public function render()
    {
        return view('livewire.karyawan.shifts.planner-jadwal');
    }
}
