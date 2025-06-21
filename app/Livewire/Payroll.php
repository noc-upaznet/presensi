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

    public $payroll_id, $no_slip, $karyawan_id, $bulan_tahun, $nip_karyawan, $divisi, $nama_karyawan;
    public $gaji_pokok, $tunjangan = [], $potongan = [], $total_gaji, $tunjangan_jabatan, $lembur_nominal, $izin_nominal, $terlambat_nominal;
    public $terlambat, $izin, $cuti, $kehadiran, $lembur;
    public $jenis_tunjangan, $jenis_potongan;
    public $persentase_bpjs = 1;
    public $bpjs_nominal = 0;

    public $persentase_bpjs_jht = 2;
    public $bpjs_jht_nominal = 0;

    public $jumlahBelumPunyaSlip;

    public function mount()
    {
        $this->selectedYear = now()->year;
        $this->selectedMonth = now()->subMonth()->format('n');
        $this->periode = now()->format('Y-m');
        $this->jenis_tunjangan = JenisTunjanganModel::all();
        $this->jenis_potongan = JenisPotonganModel::all();

        $bulanFormatted = str_pad($this->selectedMonth, 2, '0', STR_PAD_LEFT);
        $this->periode = $this->selectedYear . '-' . $bulanFormatted;

        $periode = $this->periode;

        // Hitung jumlah karyawan yang belum punya slip gaji di periode tersebut
        $this->jumlahBelumPunyaSlip = DB::table('data_karyawan')
            ->leftJoin('payroll', function($join) use ($periode) {
                $join->on('data_karyawan.id', '=', 'payroll.karyawan_id')
                    ->where('payroll.periode', '=', $periode);
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

    public function hitungTotalGaji()
    {
        $totalTunjangan = collect($this->tunjangan)->sum(fn($item) => (int) $item['nominal']);
        $totalPotongan = collect($this->potongan)->sum(fn($item) => (int) $item['nominal']);

        // Jangan hitung otomatis BPJS kalau kamu ingin input manual
        // Tapi validasi bahwa nilai sudah ada
        $bpjs = (int) $this->bpjs_nominal;
        $bpjsJht = (int) $this->bpjs_jht_nominal;

        // Hitung total gaji akhir
        $this->total_gaji = (int) $this->gaji_pokok
            + $totalTunjangan
            + (int) $this->tunjangan_jabatan
            - $totalPotongan
            - $bpjs
            - $bpjsJht;
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