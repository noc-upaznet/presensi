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
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;

class Payroll extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

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
        $this->selectedMonth = request()->query('month', now()->month);
        $this->selectedYear  = request()->query('year', now()->year);

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
        $this->hitungSlip();
    }

    public function createSlipGaji($month, $year)
    {
        return redirect()->route('create-slip-gaji-tambah', [
            'month' => $month,
            'year'  => $year,
        ]);
    }

    public function updatedSelectedMonth()
    {
        $this->hitungSlip();
    }

    public function updatedSelectedYear()
    {
        $this->hitungSlip();
    }

    private function hitungSlip()
    {
        // Format periode (YYYY-MM) dari bulan yang dipilih
        $bulanFormatted = str_pad($this->selectedMonth, 2, '0', STR_PAD_LEFT);
        $this->periode = $this->selectedYear . '-' . $bulanFormatted;

        // Hitung tanggal cutoff
        $cutoffEnd = \Carbon\Carbon::createFromDate($this->selectedYear, $this->selectedMonth, 25);
        $cutoffStart = $cutoffEnd->copy()->subMonthNoOverflow()->setDay(26);

        $this->cutoffStart = $cutoffStart->format('Y-m-d');
        $this->cutoffEnd = $cutoffEnd->format('Y-m-d');

        $selectedEntitas = $this->currentEntitas;

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

    public function editPayroll($id)
    {
        $this->periode = $this->selectedYear . '-' . str_pad($this->selectedMonth, 2, '0', STR_PAD_LEFT);
        // dd($this->periode);
        return redirect()
            ->route('edit-payroll', $id)
            ->with(['periode' => $this->periode]);
    }

    // public function saveEdit()
    // {
    //     $payroll = PayrollModel::findOrFail($this->payroll_id);

    //     $data = [
    //         'no_slip' => $this->no_slip,
    //         'karyawan_id' => $this->karyawan_id,
    //         'periode' => $this->bulan_tahun,
    //         'nip_karyawan' => $this->nip_karyawan,
    //         'divisi' => $this->divisi,
    //         'gaji_pokok' => $this->gaji_pokok,
    //         'tunjangan_jabatan' => $this->tunjangan_jabatan,
    //         'lembur' => $this->lembur_nominal,
    //         'insentif' => $this->insentif,
    //         'izin' => $this->izin_nominal,
    //         'terlambat' => $this->terlambat_nominal,
    //         'tunjangan' => json_encode($this->tunjangan),
    //         'potongan' => json_encode($this->potongan),
    //         'bpjs' => $this->bpjs_nominal,
    //         'bpjs_jht' => $this->bpjs_jht_nominal,
    //         'total_gaji' => $this->total_gaji,
    //     ];
    //     // dd($data);
    //     $payroll->update($data);

    //     $this->dispatch('swal', params: [
    //         'title' => 'Data Updated',
    //         'icon' => 'success',
    //         'text' => 'Data has been updated successfully'
    //     ]);

    //     $this->dispatch('editPayrollModal', action: 'hide');
    // }

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

    public function publishPayroll($id)
    {
        $payroll = PayrollModel::findOrFail($id);
        $payroll->published = 1; // Set published ke 1
        $payroll->save();

        $this->dispatch('swal', params: [
            'title' => 'Payroll Published',
            'icon' => 'success',
            'text' => 'Payroll has been published successfully'
        ]);
    }

    public function showModal($id = null)
    {
        $this->periode = $this->selectedYear . '-' . str_pad($this->selectedMonth, 2, '0', STR_PAD_LEFT);
        // dd($this->periode);
        $this->dispatch('modalPayroll', action: 'show', periode: $this->periode, id: encrypt($id));
    }

    public function showModalEks()
    {
        $this->periode = $this->selectedYear . '-' . str_pad($this->selectedMonth, 2, '0', STR_PAD_LEFT);
        // dd($this->periode);
        $this->dispatch('modalPayrollEks', action: 'show', periode: $this->periode);
    }

    public function render()
    {
        $entitasNama = session('selected_entitas', 'UHO');
        $entitasAktif = M_Entitas::where('nama', $entitasNama)->first();
        $entitasIdAktif = $entitasAktif?->id;

        // Filter periode
        $periode = null;
        if ($this->selectedMonth && $this->selectedYear) {
            $periode = $this->selectedYear . '-' . str_pad($this->selectedMonth, 2, '0', STR_PAD_LEFT);
        }

        $dataQuery = PayrollModel::with('getKaryawan')
            ->where('entitas_id', $entitasIdAktif)
            ->where('titip', 0)
            ->when($this->selectedStatus !== null && $this->selectedStatus !== '', function ($q) {
                $q->where('accepted', $this->selectedStatus);
            });

        $data2Query = PayrollModel::with('getKaryawan')
            ->where('entitas_id', $entitasIdAktif)
            ->where('titip', 1)
            ->whereHas('getKaryawan', function ($q) use ($entitasNama) {
                $q->where('entitas_id', '!=', $entitasNama);
            })
            ->when($this->selectedStatus !== null && $this->selectedStatus !== '', function ($q) {
                $q->where('accepted', $this->selectedStatus);
            });

        // Apply filter periode jika ada
        if ($periode) {
            $dataQuery->where('periode', $periode);
            $data2Query->where('periode', $periode);
        }

        $data = $dataQuery->orderBy('created_at', 'desc')->paginate($this->perPage);
        $data2 = $data2Query->orderBy('created_at', 'desc')->paginate($this->perPage);

        return view('livewire.payroll', [
            'data' => $data,
            'data2' => $data2
        ]);
    }
  
}