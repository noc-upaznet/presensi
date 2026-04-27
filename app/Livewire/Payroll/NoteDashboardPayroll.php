<?php

namespace App\Livewire\Payroll;

use App\Models\M_DataKaryawan;
use App\Models\M_Entitas;
use App\Traits\CutoffPayrollTrait;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class NoteDashboardPayroll extends Component
{
    public $selectedYear;
    public $selectedMonth;
    public $periode;

    public $cutoffStart;
    public $cutoffEnd;
    public $currentEntitas;

    public $note;
    public $tittle;
    public $noteId;

    public $year;
    public $month;
    public $months     = [];
    public $indicators = [];
    public $totals     = [];
    public $totalsTitip = [];

    public $indicatorsTitip = [];

    public $branchUHO = false;

    public $staticRows = [
        'non_titip' => [
            'biaya_tambahan' => [],
            'kenaikan_gaji' => [],
        ],
        'titip' => [
            'biaya_tambahan' => [],
            'kenaikan_gaji' => [],
        ],
    ];

    use CutoffPayrollTrait;

    public function mount($year = null, $month = null)
    {
        \Carbon\Carbon::setLocale('id');

        $this->selectedMonth = request()->query('month', now()->month);
        $this->selectedYear  = request()->query('year', now()->year);

        // Format periode (YYYY-MM) dari bulan yang dipilih
        $bulanFormatted = str_pad($this->selectedMonth, 2, '0', STR_PAD_LEFT);
        $this->periode  = $this->selectedYear . '-' . $bulanFormatted;

        // Hitung tanggal cutoff
        $cutoffEnd   = \Carbon\Carbon::createFromDate($this->selectedYear, $this->selectedMonth, 25);
        $cutoffStart = $cutoffEnd->copy()->subMonthNoOverflow()->setDay(26);

        $this->cutoffStart = $cutoffStart->format('Y-m-d');
        $this->cutoffEnd   = $cutoffEnd->format('Y-m-d');

        // Entitas
        $selectedEntitas = session('selected_entitas', 'UHO');
        if (!session()->has('selected_entitas')) {
            session(['selected_entitas' => 'UHO']);
            $selectedEntitas = 'UHO';
        }

        if ($selectedEntitas !== 'UHO') {
            $this->branchUHO = false;
        } else {
            $this->branchUHO = true;
        }

        $this->currentEntitas = $selectedEntitas;


        // Load data tabel indikator payroll
        $this->year  = $this->selectedYear;
        $this->month = $this->selectedMonth;

        $this->loadPeriods();
        $this->loadIndicators();
        $this->loadIndicatorsTitip();
        $this->loadTotals();
        $this->loadTotalsTitip();
        $this->loadStaticRows();
    }

    protected $listeners = ['monthYearUpdated' => 'handleMonthYearUpdate'];

    public function handleMonthYearUpdate($month, $year)
    {
        $this->selectedMonth = $month;
        $this->selectedYear  = $year;
        $this->year          = $year;
        $this->month         = $month;

        $cutoffEnd   = \Carbon\Carbon::createFromDate($year, $month, 25);
        $cutoffStart = $cutoffEnd->copy()->subMonthNoOverflow()->setDay(26);

        $this->cutoffStart = $cutoffStart->format('Y-m-d');
        $this->cutoffEnd   = $cutoffEnd->format('Y-m-d');

        $this->loadPeriods();
        $this->loadIndicators();
        $this->loadIndicatorsTitip();
        $this->loadTotals();
        $this->loadTotalsTitip();
        $this->loadStaticRows();
    }

    private function loadPeriods(): void
    {
        $this->months = $this->getPeriods()
            ->pluck('label')
            ->toArray();
    }

    private function getPeriods(): \Illuminate\Support\Collection
    {
        return collect([-1, 0])->map(function ($offset) {
            $date   = \Carbon\Carbon::create($this->year, $this->month, 1)->addMonths($offset);
            $cutoff = $this->resolveCutoff($date->year, $date->month, 'cutoff_25');

            return [
                'label'   => \Carbon\Carbon::parse($cutoff['bulanTahun'])->translatedFormat('F'),
                'periode' => \Carbon\Carbon::parse($cutoff['bulanTahun'])->format('Y-m'), // tambahkan ini
                'start'   => $cutoff['start'],
                'end'     => $cutoff['end'],
            ];
        });
    }

    private function getIndicatorDefs($titip = 0): array
    {
        $branch_id = M_Entitas::where('nama', $this->currentEntitas)->value('id');

        return [
            [
                'label' => 'Lembur Karyawan',
                'query' => fn($start, $end) => DB::table('payroll')
                    ->where('entitas_id', $branch_id)
                    ->where('titip', $titip)
                    ->whereBetween('periode', [$start, $end])
                    ->sum(DB::raw('lembur + lembur_libur')),
            ],
            [
                'label' => 'Sodaqoh / Punishment Karyawan',
                'query' => fn($start, $end) => DB::table('payroll')
                    ->where('entitas_id', $branch_id)
                    ->where('titip', $titip)
                    ->whereBetween('periode', [$start, $end])
                    ->sum('terlambat'),
            ],
            [
                'label' => 'Potongan Izin Karyawan',
                'query' => fn($start, $end) => DB::table('payroll')
                    ->where('entitas_id', $branch_id)
                    ->where('titip', $titip)
                    ->whereBetween('periode', [$start, $end])
                    ->sum('izin'),
            ],
            [
                'label' => 'Tunjangan Kehadiran',
                'query' => fn($start, $end) => DB::table('payroll')
                    ->where('entitas_id', $branch_id)
                    ->where('titip', $titip)
                    ->whereBetween('periode', [$start, $end])
                    ->get()
                    ->sum(function ($row) {
                        $tunjangan = json_decode($row->tunjangan, true) ?? [];
                        $item = collect($tunjangan)->firstWhere('nama', 'Tunjangan Kehadiran');
                        return $item['nominal'] ?? 0;
                    }),
            ],
            [
                'label' => 'Tunjangan Kebudayaan (+-)',
                'query' => fn($start, $end) => DB::table('payroll')
                    ->where('entitas_id', $branch_id)
                    ->where('titip', $titip)
                    ->whereBetween('periode', [$start, $end])
                    ->sum('tunjangan_kebudayaan'),
            ],
        ];
    }

    // -------------------------------------------------------
    // Load Totals — dari kolom total_gaji
    // -------------------------------------------------------

    private function loadTotals(): void
    {
        $periods = $this->getPeriods();

        $entitas = $this->currentEntitas;

        $karyawanIds = M_DataKaryawan::where('entitas', $entitas)->pluck('id');

        $this->totals = $periods->mapWithKeys(function ($period) use ($karyawanIds) {

            $rows = DB::table('payroll')
                ->whereIn('karyawan_id', $karyawanIds) // 🔥 samakan
                ->where('titip', 0)
                ->where('periode', $period['periode'])
                ->get();

            $total = $rows->sum(function ($item) {

                $tunjanganArray = collect(json_decode($item->tunjangan, true) ?? []);
                $potonganArray  = collect(json_decode($item->potongan, true) ?? []);

                $pendapatan =
                    ($item->gaji_pokok ?? 0)
                    + ($item->tunjangan_jabatan ?? 0)
                    + ($item->lembur ?? 0)
                    + ($item->lembur_libur ?? 0)
                    + ($item->tunjangan_kebudayaan ?? 0)
                    + ($item->transport ?? 0)
                    + ($item->uang_makan ?? 0)
                    + ($item->fee_sharing ?? 0)
                    + ($item->insentif ?? 0)
                    + ($item->inov_reward ?? 0)
                    + $tunjanganArray->sum('nominal');

                $excludePotongan = ['pph 21', 'pph21', 'potongan kebudayaan'];

                $potongan =
                    ($item->izin ?? 0)
                    + ($item->terlambat ?? 0)
                    + ($item->churn ?? 0)
                    + $potonganArray
                    ->filter(fn($p) => !in_array(strtolower($p['nama'] ?? ''), $excludePotongan))
                    ->sum('nominal');

                return $pendapatan - $potongan;
            });

            return [$period['label'] => $total];
        })->toArray();

        $bulanIni  = $this->totals[$this->months[1]] ?? 0;
        $bulanLalu = $this->totals[$this->months[0]] ?? 0;

        $this->totals['grand'] = $bulanIni - $bulanLalu;
    }

    private function loadTotalsTitip(): void
    {
        $periods = $this->getPeriods();

        $entitas = $this->currentEntitas;

        $karyawanIds = M_DataKaryawan::where('entitas', $entitas)->pluck('id');

        $this->totalsTitip = $periods->mapWithKeys(function ($period) use ($karyawanIds) {

            $rows = DB::table('payroll')
                ->whereIn('karyawan_id', $karyawanIds)
                ->where('titip', 1)
                ->where('periode', $period['periode'])
                ->get();

            $total = $rows->sum(function ($item) {

                $tunjanganArray = collect(json_decode($item->tunjangan, true) ?? []);
                $potonganArray  = collect(json_decode($item->potongan, true) ?? []);

                $pendapatan =
                    ($item->gaji_pokok ?? 0)
                    + ($item->tunjangan_jabatan ?? 0)
                    + ($item->lembur ?? 0)
                    + ($item->lembur_libur ?? 0)
                    + ($item->tunjangan_kebudayaan ?? 0)
                    + ($item->transport ?? 0)
                    + ($item->uang_makan ?? 0)
                    + ($item->fee_sharing ?? 0)
                    + ($item->insentif ?? 0)
                    + ($item->inov_reward ?? 0)
                    + $tunjanganArray->sum('nominal');

                $excludePotongan = ['pph 21', 'pph21', 'potongan kebudayaan'];

                $potongan =
                    ($item->izin ?? 0)
                    + ($item->terlambat ?? 0)
                    + ($item->churn ?? 0)
                    + $potonganArray
                    ->filter(fn($p) => !in_array(strtolower($p['nama'] ?? ''), $excludePotongan))
                    ->sum('nominal');

                return $pendapatan - $potongan;
            });

            return [$period['label'] => $total];
        })->toArray();

        $bulanIni  = $this->totalsTitip[$this->months[1]] ?? 0;
        $bulanLalu = $this->totalsTitip[$this->months[0]] ?? 0;

        $this->totalsTitip['grand'] = $bulanIni - $bulanLalu;
    }

    // -------------------------------------------------------
    // Load Indicators
    // -------------------------------------------------------

    private function loadIndicators(): void
    {
        $periods   = $this->getPeriods();
        $branch_id = M_Entitas::where('nama', $this->currentEntitas)->value('id');

        $currentBulanTahun = $this->resolveCutoff($this->year, $this->month, 'cutoff_25')['bulanTahun'];

        $notes = DB::table('notes_payroll')
            ->where('date', $currentBulanTahun)
            ->where('branch_id', $branch_id) // ✅ pakai branch_id bukan nama entitas
            ->pluck('note')
            ->toArray();

        $kesimpulan = implode(' ', $notes);

        $this->indicators = collect($this->getIndicatorDefs())
            ->map(function ($def, $index) use ($periods, $kesimpulan) {

                $values = $periods->mapWithKeys(fn($period) => [
                    $period['label'] => ($def['query'])($period['start'], $period['end']),
                ])->toArray();

                $monthValues = array_values($values);
                $bulanIni    = $monthValues[1];
                $bulanLalu   = $monthValues[0];
                $total       = $bulanIni - $bulanLalu;

                return [
                    'label'      => $def['label'],
                    'values'     => $values,
                    'total'      => $total,
                ];
            })
            ->toArray();
    }

    private function loadIndicatorsTitip(): void
    {
        $periods = $this->getPeriods();

        $this->indicatorsTitip = collect($this->getIndicatorDefs(1))
            ->map(function ($def) use ($periods) {

                $values = $periods->mapWithKeys(fn($period) => [
                    $period['label'] => ($def['query'])($period['start'], $period['end']),
                ])->toArray();

                $monthValues = array_values($values);
                $bulanIni    = $monthValues[1];
                $bulanLalu   = $monthValues[0];

                return [
                    'label'  => $def['label'],
                    'values' => $values,
                    'total'  => $bulanIni - $bulanLalu,
                ];
            })
            ->toArray();
    }

    public $editMode = [
        'non_titip' => [
            'kenaikan_gaji' => false,
            'biaya_tambahan' => false,
        ],
        'titip' => [
            'kenaikan_gaji' => false,
            'biaya_tambahan' => false,
        ],
    ];

    public function toggleEdit($type, $key)
    {
        $this->editMode[$type][$key] = !$this->editMode[$type][$key];
    }

    public function saveStaticRowDirect(string $key, string $periode, string $month, string $raw, $titip = 0): void
    {
        if (!in_array($key, ['biaya_tambahan', 'kenaikan_gaji'])) return;

        $branch_id = M_Entitas::where('nama', $this->currentEntitas)->value('id');

        $nominal = (int) preg_replace('/[^0-9]/', '', $raw);

        DB::table('note_static_rows')->updateOrInsert(
            [
                'branch_id' => $branch_id,
                'periode'   => $periode, // 🔥 langsung dari loop
                'key'       => $key,
                'month'     => $month,
                'titip'     => $titip,
            ],
            [
                'nominal'    => $nominal,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
    }

    public function saveStaticBatch($type, $key)
    {
        $titip = $type === 'titip' ? 1 : 0;

        $periods = $this->getPeriods()->values(); // 🔥 reset index

        foreach ($this->months as $i => $month) {

            $periode = $periods[$i]['periode']; // 🔥 pasti sesuai

            $value = $this->staticRows[$type][$key]['value_' . $month] ?? 0;

            $this->saveStaticRowDirect($key, $periode, $month, (string) $value, $titip);
        }
        unset($this->staticRows[$type][$key]);
        $this->loadStaticRows();

        $this->editMode[$type][$key] = false;
    }

    private function loadStaticRows(): void
    {
        $branch_id = M_Entitas::where('nama', $this->currentEntitas)->value('id');

        $periods = $this->getPeriods();

        foreach (['non_titip' => 0, 'titip' => 1] as $type => $titip) {
            foreach (['biaya_tambahan', 'kenaikan_gaji'] as $key) {

                $rows = DB::table('note_static_rows')
                    ->where('branch_id', $branch_id)
                    ->where('key', $key)
                    ->where('titip', $titip)
                    ->whereIn('periode', $periods->pluck('periode'))
                    ->get();

                // mapping manual
                $mapped = $rows->mapWithKeys(fn($row) => [
                    $row->periode => $row->nominal
                ]);


                $values = [];

                foreach ($periods as $period) {
                    $values['value_' . $period['label']] = $mapped[$period['periode']] ?? 0;
                }

                $bulanIni  = $values['value_' . $periods[1]['label']] ?? 0;
                $bulanLalu = $values['value_' . $periods[0]['label']] ?? 0;

                $this->staticRows[$type][$key] = array_merge($values, [
                    'total' => $bulanIni - $bulanLalu,
                ]);
            }
        }
    }

    public function saveStaticRow(string $key, string $month, string $raw, $titip = 0): void
    {
        if (!in_array($key, ['biaya_tambahan', 'kenaikan_gaji'])) return;

        $branch_id = M_Entitas::where('nama', $this->currentEntitas)->value('id');

        // 🔥 cari periode berdasarkan label bulan
        $period = collect($this->getPeriods())
            ->firstWhere('label', $month);

        if (!$period) return;

        $periode = $period['periode']; // 🔥 INI YANG BENAR

        $nominal = (int) preg_replace('/[^0-9]/', '', $raw);

        DB::table('note_static_rows')->updateOrInsert(
            [
                'branch_id' => $branch_id,
                'periode'   => $periode, // 🔥 sesuai kolom
                'key'       => $key,
                'month'     => $month,   // label tetap
                'titip'     => $titip,
            ],
            [
                'nominal'    => $nominal,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
    }

    public function showAddNoteModal()
    {
        $this->dispatch('addNoteModal', action: 'show');
    }

    public function saveNote()
    {
        $this->validate([
            'note'   => 'required|string|max:255',
            'tittle' => 'nullable|string|max:255',
        ]);


        DB::table('notes_payroll')->insert([
            'branch_id'  => M_Entitas::where('nama', session('selected_entitas'))->value('id'),
            'tittle'     => $this->tittle,
            'note'       => $this->note,
            'date'       => now('Asia/Jakarta')->format('Y-m-d'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->reset(['note', 'tittle']);

        $this->dispatch('swal', params: [
            'title' => 'Catatan Tersimpan',
            'icon'  => 'success',
            'text'  => 'Catatan telah disimpan dengan sukses'
        ]);

        $this->dispatch('addNoteModal', action: 'hide');
    }

    public function showEditNoteModal($id)
    {
        $note = DB::table('notes_payroll')->where('id', $id)->first();
        if ($note) {
            $this->tittle = $note->tittle;
            $this->note   = $note->note;
            $this->noteId = $note->id;
        }

        $this->dispatch('EditNoteModal', action: 'show');
    }

    public function UpdateNote()
    {
        $this->validate([
            'note'   => 'required|string|max:255',
            'tittle' => 'nullable|string|max:255',
        ]);

        if (!$this->noteId) {
            $this->dispatch('swal', params: [
                'title' => 'Gagal',
                'icon'  => 'error',
                'text'  => 'ID catatan tidak ditemukan'
            ]);
            return;
        }

        DB::table('notes_payroll')->where('id', $this->noteId)->update([
            'branch_id'  => M_Entitas::where('nama', session('selected_entitas'))->value('id'),
            'tittle'     => $this->tittle,
            'note'       => $this->note,
            'updated_at' => now(),
        ]);

        $this->reset(['note', 'tittle', 'noteId']);

        $this->dispatch('swal', params: [
            'title' => 'Catatan Diperbarui',
            'icon'  => 'success',
            'text'  => 'Catatan telah diperbarui dengan sukses'
        ]);

        $this->dispatch('EditNoteModal', action: 'hide');
    }

    public function confirmDeleteNote($id)
    {
        $this->noteId = $id;
        $this->dispatch('deleteNoteModal', action: 'show');
    }

    public function deletedNote()
    {
        if ($this->noteId) {
            DB::table('notes_payroll')->where('id', $this->noteId)->delete();

            $this->dispatch('swal', params: [
                'title'             => 'Data Deleted',
                'icon'              => 'success',
                'text'              => 'Data has been deleted successfully',
                'showConfirmButton' => false,
                'timer'             => 1500
            ]);

            $this->noteId = null;
        }
    }

    public function render()
    {
        $entitasNama = session('selected_entitas', 'UHO');
        $entitasAktif = M_Entitas::where('nama', $entitasNama)->first();
        $entitasIdAktif = $entitasAktif?->id;
        $note = DB::table('notes_payroll')
            ->whereYear('date', $this->selectedYear)
            ->whereMonth('date', $this->selectedMonth)
            ->where('branch_id', $entitasIdAktif)
            ->orderBy('date', 'desc')
            ->get();
        return view('livewire.payroll.note-dashboard-payroll', [
            'notes' => $note,
        ]);
    }
}
