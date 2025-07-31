<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use App\Models\M_Entitas;
use App\Models\PayrollModel;
use Livewire\WithPagination;
use App\Exports\PayrollExport;
use App\Models\M_DataKaryawan;
use App\Models\JenisPotonganModel;
use App\Models\JenisTunjanganModel;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Redirect;

class Payroll extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedYear;
    public $selectedMonth;
    public $selectedStatus;
    public $periode;
    public $perPage = 10;
    public $payrollIdToDelete;
    public $startDate;
    public $endDate;
    public $currentEntitas;

    public $payroll_id, $no_slip, $karyawan_id, $bulan_tahun, $nip_karyawan, $divisi, $nama_karyawan, $jabatan, $jml_psb, $insentif;
    public $gaji_pokok, $tunjangan = [], $potongan = [], $total_gaji, $tunjangan_jabatan, $lembur_nominal, $izin_nominal, $terlambat_nominal, $kebudayaan, $transport, $uang_makan, $fee_sharing;
    public $terlambat, $izin, $cuti, $kehadiran, $lembur;
    public $jenis_tunjangan, $jenis_potongan;
    public $persentase_bpjs = 1;
    public $bpjs_nominal = 0;

    public $persentase_bpjs_jht = 2;
    public $bpjs_jht_nominal = 0;

    public $jumlahBelumPunyaSlip;
    public $jumlahBelumPunyaSlipTitip;
    public $JumlahKaryawanTitip;
    public $JumlahKaryawanInternal;
    public $cutoffStart;
    public $cutoffEnd;

    public function mount()
    {
        if (Auth::user()?->current_role !== 'admin') {
            // Bisa redirect atau abort
            return redirect()->route('dashboard');
            // abort(403, 'Access Denied');
        }
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

        $this->currentEntitas = $selectedEntitas;

        // Hitung jumlah karyawan yang belum punya slip gaji di periode tersebut
        // $karyawanQuery = M_DataKaryawan::where('entitas', $selectedEntitas);

        $this->jumlahBelumPunyaSlip = M_DataKaryawan::query()
            ->where('entitas', $selectedEntitas)
            ->leftJoin('payroll', function ($join) {
                $join->on('data_karyawan.id', '=', 'payroll.karyawan_id')
                    ->where('payroll.periode', '=', $this->periode);
            })
            ->whereNull('payroll.id')
            ->count();

        $this->jumlahBelumPunyaSlipTitip = M_DataKaryawan::query()
            ->where('entitas', '!=', $selectedEntitas)
            ->leftJoin('payroll', function ($join) {
                $join->on('data_karyawan.id', '=', 'payroll.karyawan_id')
                    ->where('payroll.periode', '=', $this->periode);
            })
            ->whereNull('payroll.id')
            ->count();

        $selectedEntitasId = M_Entitas::where('nama', $selectedEntitas)->value('id');
        $this->JumlahKaryawanTitip = PayrollModel::with('getKaryawan')
            ->where('titip', 1)
            ->where('periode', $this->periode)
            ->whereHas('getKaryawan', function ($query) use ($selectedEntitasId) {
                $query->where('data_karyawan.entitas', '!=', $selectedEntitasId);
            })
            ->where('payroll.entitas_id', $selectedEntitasId)
            ->count();

        $this->JumlahKaryawanInternal = PayrollModel::with('getKaryawan')
            ->where('titip', 0)
            ->where('periode', $this->periode)
            ->where('payroll.entitas_id', $selectedEntitasId)
            ->count();

            // dd($this->JumlahKaryawanInternal);
    }

    public function toggleTitip($karyawan_id)
    {
        $userTitip = PayrollModel::findOrFail($karyawan_id);
        $userTitip->titip = !$userTitip->titip;
        $userTitip->save();
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
                'kebudayaan',
                'transport',
                'uang_makan',
                'fee_sharing',
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
            + (int) $this->kebudayaan
            + (int) $this->fee_sharing
            + $insentif
            + (int) $this->transport
            + (int) $this->uang_makan
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
            $this->kebudayaan = $payroll->tunjangan_kebudayaan;
            $this->lembur_nominal = $payroll->lembur;
            $this->transport = $payroll->transport;
            $this->fee_sharing = $payroll->fee_sharing;
            $this->izin_nominal = $payroll->izin;
            $this->terlambat_nominal = $payroll->terlambat;
            $this->tunjangan = json_decode($payroll->tunjangan, true) ?? [];
            $this->potongan = json_decode($payroll->potongan, true) ?? [];
            $this->bpjs_nominal = $payroll->bpjs ?? 0;
            $this->bpjs_jht_nominal = $payroll->bpjs_jht ?? 0;

            // Hitung balik persentase jika gaji pokok ada
            // if ($this->gaji_pokok > 0) {
            //     $this->persentase_bpjs = round(($this->bpjs_nominal / $this->gaji_pokok) * 100, 2);
            //     $this->persentase_bpjs_jht = round(($this->bpjs_jht_nominal / $this->gaji_pokok) * 100, 2);
            // }
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

    // public function UpdatedSelectedStatus()
    // {
    //     // dd('oke');
    //     $query = PayrollModel::with('getKaryawan');
    //     // Filter status karyawan
    //     if ($this->selectedStatus === 'titip') {
    //         $query->whereHas('getKaryawan', function ($q) {
    //             $q->where('titip', 1);
    //         });
    //     } elseif ($this->selectedStatus === 'tetap') {
    //         $query->whereHas('getKaryawan', function ($q) {
    //             $q->whereNull('titip')->orWhere('titip', 0);
    //         });
    //     }
    // }

    // public function exportExcel()
    // {
    //     // Validasi tanggal (optional)
    //     $this->validate([
    //         'startDate' => 'required|date',
    //         'endDate' => 'required|date|after_or_equal:startDate',
    //     ]);

    //     return Excel::download(new PayrollExport($this->startDate, $this->endDate), 'payroll.xlsx');
    // }

    public function showModal()
    {
        $this->dispatch('modalPayroll', action: 'show');
    }

    public function showModalEks()
    {
        $this->dispatch('modalPayrollEks', action: 'show');
    }

    public function render()
    {
        $entitasNama = session('selected_entitas', 'UHO');
        $entitasAktif = M_Entitas::where('nama', $entitasNama)->first();
        $entitasIdAktif = $entitasAktif?->id;
        // dd($entitasAktif);

        // Filter periode
        $periode = null;
        if ($this->selectedMonth && $this->selectedYear) {
            $periode = $this->selectedYear . '-' . str_pad($this->selectedMonth, 2, '0', STR_PAD_LEFT);
        }

        $dataQuery = PayrollModel::with('getKaryawan')
            ->where('entitas_id', $entitasIdAktif)
            ->where('titip', 0);
        
        $data2Query = PayrollModel::with('getKaryawan')
            ->where('entitas_id', $entitasIdAktif)
            ->where('titip', 1)
            ->whereHas('getKaryawan', function ($q) use ($entitasNama) {
                $q->where('entitas_id', '!=', $entitasNama);
            });

        // Apply filter periode jika ada
        if ($periode) {
            $dataQuery->where('periode', $periode);
            $data2Query->where('periode', $periode);
        }

        $data = $dataQuery->orderBy('created_at', 'desc')->paginate($this->perPage);
        $data2 = $data2Query->orderBy('created_at', 'desc')->paginate($this->perPage);
        // dd($data2);


        return view('livewire.payroll', [
            'data' => $data,
            'data2' => $data2
        ]);
    }    
}