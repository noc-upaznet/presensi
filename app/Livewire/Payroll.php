<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\PayrollModel;
use Livewire\WithPagination;
use App\Exports\PayrollExport;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\JenisPotonganModel;
use Illuminate\Support\Facades\DB;
use App\Models\JenisTunjanganModel;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Payroll extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedYear;
    public $selectedMonth;
    public $periode;
    public $perPage = 10;
    public $payrollIdToDelete;
    public $startDate;
    public $endDate;

    public $payroll_id, $no_slip, $karyawan_id, $bulan_tahun, $nip_karyawan, $divisi, $nama_karyawan, $jabatan, $jml_psb, $insentif;
    public $gaji_pokok, $tunjangan = [], $potongan = [], $total_gaji, $tunjangan_jabatan, $lembur_nominal, $izin_nominal, $terlambat_nominal;
    public $terlambat, $izin, $cuti, $kehadiran, $lembur;
    public $jenis_tunjangan, $jenis_potongan;
    public $persentase_bpjs = 1;
    public $bpjs_nominal = 0;

    public $persentase_bpjs_jht = 2;
    public $bpjs_jht_nominal = 0;

    public $jumlahBelumPunyaSlip;
    public $cutoffStart;
    public $cutoffEnd;

    public function mount()
    {
        $this->selectedYear = now()->year;
        $this->selectedMonth = now()->subMonth()->format('n');

        // Format periode (YYYY-MM) dari bulan yang dipilih
        $bulanFormatted = str_pad($this->selectedMonth, 2, '0', STR_PAD_LEFT);
        $this->periode = $this->selectedYear . '-' . $bulanFormatted;

        // Hitung tanggal cutoff
        $cutoffEnd = \Carbon\Carbon::createFromDate($this->selectedYear, $this->selectedMonth, 25);
        $cutoffStart = $cutoffEnd->copy()->subMonthNoOverflow()->setDay(26);

        $this->cutoffStart = $cutoffStart->format('Y-m-d');
        $this->cutoffEnd = $cutoffEnd->format('Y-m-d');

        $this->jenis_tunjangan = JenisTunjanganModel::all();
        $this->jenis_potongan = JenisPotonganModel::all();

        // Entitas (misalnya: UHO)
        $selectedEntitas = session('selected_entitas', 'UHO');
        if (!session()->has('selected_entitas')) {
            session(['selected_entitas' => 'UHO']);
            $selectedEntitas = 'UHO';
        }

        // Hitung jumlah karyawan yang belum punya slip gaji di periode tersebut
        $karyawanQuery = DB::table('data_karyawan')->where('entitas', $selectedEntitas);

        $this->jumlahBelumPunyaSlip = $karyawanQuery
            ->leftJoin('payroll', function ($join) {
                $join->on('data_karyawan.id', '=', 'payroll.karyawan_id')
                    ->where('payroll.periode', '=', $this->periode);
            })
            ->whereNull('payroll.id')
            ->count();
    }



    public function export()
    {
        $bulanFormatted = str_pad($this->selectedMonth, 2, '0', STR_PAD_LEFT);
        $periode = $this->selectedYear . '-' . $bulanFormatted;

        $periodeLokal = Carbon::createFromFormat('Y-m', $periode)
        ->locale('id')
        ->translatedFormat('F Y');
        $filename = 'slip_gaji_' . $periodeLokal . '.xlsx';

        return Excel::download(new PayrollExport($periode, $periodeLokal), $filename);
    }

    // public function export(): StreamedResponse
    // {
    //     $bulanFormatted = str_pad($this->selectedMonth, 2, '0', STR_PAD_LEFT);
    //     $periode = $this->selectedYear . '-' . $bulanFormatted;

    //     // Ambil data dari tabel payroll sesuai periode
    //     $data = DB::table('payroll')
    //         ->join('data_karyawan', 'payroll.karyawan_id', '=', 'data_karyawan.id')
    //         ->where('payroll.periode', $periode)
    //         ->select(
    //             'payroll.no_slip',
    //             'data_karyawan.nama_karyawan',
    //             'data_karyawan.nip_karyawan as nip_karyawan',
    //             'data_karyawan.divisi',
    //             'payroll.periode',
    //             'payroll.total_gaji'
    //         )
    //         ->get();

    //     $filename = 'slip_gaji_' . $periode . '.csv';

    //     return Response::streamDownload(function () use ($data) {
    //         $handle = fopen('php://output', 'w');

    //         // Header CSV
    //         fputcsv($handle, ['No Slip', 'Nama Karyawan', 'NIP', 'Divisi', 'Periode', 'Total Gaji']);

    //         // Data baris
    //         foreach ($data as $row) {
    //             fputcsv($handle, [
    //                 $row->no_slip,
    //                 $row->nama_karyawan,
    //                 $row->nip_karyawan,
    //                 $row->divisi,
    //                 $row->periode,
    //                 $row->total_gaji
    //             ]);
    //         }

    //         fclose($handle);
    //     }, $filename);
    // }

    public function downloadSlip($id)
    {
        return Redirect::route('slip-gaji.download', ['id' => $id]);
    }

    public function setPeriode()
    {
        if ($this->selectedYear && $this->selectedMonth) {
            $this->periode = $this->selectedYear . '-' . str_pad($this->selectedMonth, 2, '0', STR_PAD_LEFT);
        }
    }

    public function addTunjangan()
    {
        $this->tunjangan[] = ['nama' => '', 'nominal' => 0];
    }

    public function removeTunjangan($index)
    {
        unset($this->tunjangan[$index]);
        $this->tunjangan = array_values($this->tunjangan);
        $this->hitungTotalGaji();
    }

    public function addPotongan()
    {
        $this->potongan[] = ['nama' => '', 'nominal' => 0];
    }

    public function removePotongan($index)
    {
        unset($this->potongan[$index]);
        $this->potongan = array_values($this->potongan);
        $this->hitungTotalGaji();
    }

    public function updated($propertyName)
    {
        if (
            str($propertyName)->startsWith('tunjangan') ||
            str($propertyName)->startsWith('potongan') ||
            in_array($propertyName, [
                'gaji_pokok',
                'tunjangan_jabatan',
                'bpjs_nominal',
                'bpjs_jht_nominal',
                'persentase_bpjs',
                'persentase_bpjs_jht',
            ])
        ) {
            $this->hitungTotalGaji();
        }
    }

    public function updatedJmlPsb()
    {
        if ($this->isSalesPosition()) {
            $insentifMapping = [
                1 => [1000000, 50000],
                2 => [1000000, 100000],
                3 => [1000000, 150000],
                4 => [1000000, 200000],
                5 => [1000000, 250000],
                6 => [1160000, 300000],
                7 => [1160000, 350000],
                8 => [1160000, 400000],
                9 => [1160000, 450000],
                10 => [1160000, 500000],
                11 => [1508000, 825000],
                12 => [1508000, 900000],
                13 => [1508000, 975000],
                14 => [1508000, 1050000],
                15 => [1508000, 1125000],
                16 => [1508000, 1200000],
                17 => [1508000, 1275000],
                18 => [1508000, 1350000],
                19 => [1508000, 1425000],
                20 => [2320000, 1700000],
                21 => [2320000, 1785000],
                22 => [2320000, 1870000],
                23 => [2320000, 1955000],
                24 => [2320000, 2040000],
                25 => [2320000, 2125000],
                26 => [2320000, 2210000],
                27 => [2320000, 2295000],
                28 => [2320000, 2380000],
                29 => [2320000, 2465000],
                30 => [2320000, 2550000],
            ];

            if (isset($insentifMapping[$this->jml_psb])) {
                [$upah, $insentif] = $insentifMapping[$this->jml_psb];

                // gaji pokok = 75% dari upah, tunjangan jabatan = 25% dari upah
                $this->gaji_pokok = round($upah * 0.75);
                $this->tunjangan_jabatan = round($upah * 0.25);
                $this->insentif = $insentif;

                $this->hitungTotalGaji();
            } else {
                $this->insentif = 0;
            }
        }
    }

    public function hitungTotalGaji()
    {
        $totalTunjangan = collect($this->tunjangan)->sum(fn($item) => (int) $item['nominal']);
        $totalPotongan = collect($this->potongan)->sum(fn($item) => (int) $item['nominal']);

        $bpjs = (int) $this->bpjs_nominal;
        $bpjsJht = (int) $this->bpjs_jht_nominal;
        $insentif = (int) $this->insentif;

        // Hitung total gaji akhir, tambahkan insentif
        $this->total_gaji = (int) $this->gaji_pokok
            + $totalTunjangan
            + (int) $this->tunjangan_jabatan
            + $insentif
            - $totalPotongan
            - $bpjs
            - $bpjsJht;
    }

    public function isSalesPosition()
    {
        return in_array(strtolower($this->jabatan), ['sales', 'sm', 'sales marketing']);
    }

    public function editPayroll($id)
    {
        $payroll = PayrollModel::with('getKaryawan')->find($id);
        // dd($payroll);
        if ($payroll) {
            $this->payroll_id = $payroll->id; // tambahkan ini untuk menyimpan ID (jika ingin update nanti)
            $this->no_slip = $payroll->no_slip;
            $this->karyawan_id = $payroll->karyawan_id;
            $this->nama_karyawan = $payroll->getKaryawan->nama_karyawan;
            $this->bulan_tahun = $payroll->periode;
            $this->nip_karyawan = $payroll->nip_karyawan;
            $this->divisi = $payroll->divisi;
            $this->jabatan = $payroll->getKaryawan->jabatan;
            $this->jml_psb = $payroll->jml_psb;
            $this->insentif = $payroll->insentif;
            $this->gaji_pokok = $payroll->gaji_pokok;
            $this->tunjangan_jabatan = $payroll->tunjangan_jabatan;
            $this->lembur_nominal = $payroll->lembur;
            $this->izin_nominal = $payroll->izin;
            $this->terlambat_nominal = $payroll->terlambat;
            $this->tunjangan = json_decode($payroll->tunjangan, true) ?? [];
            $this->potongan = json_decode($payroll->potongan, true) ?? [];
            $this->bpjs_nominal = $payroll->bpjs ?? 0;
            $this->bpjs_jht_nominal = $payroll->bpjs_jht ?? 0;

            // Hitung balik persentase jika gaji pokok ada
            if ($this->gaji_pokok > 0) {
                $this->persentase_bpjs = round(($this->bpjs_nominal / $this->gaji_pokok) * 100, 2);
                $this->persentase_bpjs_jht = round(($this->bpjs_jht_nominal / $this->gaji_pokok) * 100, 2);
            }
            $this->terlambat = $payroll->terlambat ?? 0;
            $this->izin = $payroll->izin ?? 0;
            $this->cuti = $payroll->cuti ?? 0;
            $this->kehadiran = $payroll->kehadiran ?? 0;
            $this->lembur = $payroll->lembur ?? 0;
            $this->total_gaji = $payroll->total_gaji;


            $this->dispatch('editPayrollModal', action: 'show'); // trigger untuk buka modal (optional)
        }
    }

    public function saveEdit()
    {
        $payroll = PayrollModel::findOrFail($this->payroll_id);

        $data = [
            'no_slip' => $this->no_slip,
            'karyawan_id' => $this->karyawan_id,
            'periode' => $this->bulan_tahun,
            'nip_karyawan' => $this->nip_karyawan,
            'divisi' => $this->divisi,
            'gaji_pokok' => $this->gaji_pokok,
            'tunjangan_jabatan' => $this->tunjangan_jabatan,
            'lembur' => $this->lembur_nominal,
            'insentif' => $this->insentif,
            'izin' => $this->izin_nominal,
            'terlambat' => $this->terlambat_nominal,
            'tunjangan' => json_encode($this->tunjangan),
            'potongan' => json_encode($this->potongan),
            'bpjs' => $this->bpjs_nominal,
            'bpjs_jht' => $this->bpjs_jht_nominal,
            'total_gaji' => $this->total_gaji,
        ];
        // dd($data);
        $payroll->update($data);

        $this->dispatch('swal', params: [
            'title' => 'Data Updated',
            'icon' => 'success',
            'text' => 'Data has been updated successfully'
        ]);

        $this->dispatch('editPayrollModal', action: 'hide');
    }

    public function confirmHapusPayroll($id)
    {
        $this->payrollIdToDelete = $id;
        // dd($this->payrollIdToDelete);
        $this->dispatch('hapusPayrollModal', action: 'show');
    }

    public function deletePayroll()
    {
        if ($this->payrollIdToDelete) {
            PayrollModel::find($this->payrollIdToDelete)?->delete();

            // Bisa tambahkan refresh/pagination data di sini kalau perlu
            // $this->data = Payroll::latest()->paginate($this->perPage);

            // $this->dispatch('dataPayrollTerhapus');
            $this->dispatch(
                'swal', params: [
                'title' => 'Data Saved',
                'icon' => 'success',
                'text' => 'Data has been saved successfully',
                'showConfirmButton' => false,
                'timer' => 1500
            ]);
            $this->payrollIdToDelete = null;
        }
    }

    public function exportExcel()
    {
        // Validasi tanggal (optional)
        $this->validate([
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
        ]);

        return Excel::download(new PayrollExport($this->startDate, $this->endDate), 'payroll.xlsx');
    }

    public function showModal()
    {
        $this->dispatch('modalPayroll', action: 'show');
    }

    public function render()
    {
        $query = PayrollModel::with('getKaryawan');

        // Ambil entitas dari session
        $entitasNama = session('selected_entitas', 'UHO');
        $entitas = \App\Models\M_Entitas::where('nama', $entitasNama)->first();

        if ($entitas && $entitasNama !== 'All' && $entitasNama !== 'All Branch') {
            $query->where('entitas_id', $entitas->id);
        }

        // Filter periode
        if ($this->selectedMonth && $this->selectedYear) {
            $periode = $this->selectedYear . '-' . str_pad($this->selectedMonth, 2, '0', STR_PAD_LEFT);
            $query->where('periode', $periode);
        }

        $data = $query->orderBy('created_at', 'desc')->paginate($this->perPage);

        return view('livewire.payroll', [
            'data' => $data
        ]);
    }

}