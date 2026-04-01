<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PayrollModel;
use App\Models\M_DataKaryawan;
use App\Models\M_Entitas;
use Illuminate\Support\Facades\Auth;

class DashboardPayroll extends Component
{
    public $selectedYear;
    public $selectedMonth;

    public $periode;

    public $total_gaji = 0;
    public $total_gaji_titip = 0;
    public $bpjs_kes_pt = 0;
    public $bpjs_jht_pt = 0;
    public $potongan_terlambat = 0;
    public $potongan_terlambat_titip = 0;

    public $currentEntitas;

    public function mount()
    {
        $this->periode = now()->format('Y-m');

        $branchId = Auth::user()->branch_id;
        $entitasName = M_Entitas::find($branchId)?->nama;
        $this->currentEntitas = $entitasName;
        $this->countGaji();
    }

    public function updatedPeriode()
    {
        $this->countGaji();
    }

    public function countGaji()
    {
        $branchId = Auth::user()->branch_id;
        $entitasName = M_Entitas::find($branchId)?->nama;
        $karyawanIds = M_DataKaryawan::where('entitas', $entitasName)->pluck('id');

        $hitungGaji = function ($items) {
            return $items->sum(function ($item) {

                $tunjanganArray = collect(json_decode($item->tunjangan, true) ?? []);
                $potonganArray  = collect(json_decode($item->potongan, true) ?? []);

                $pendapatan =
                    ($item->gaji_pokok ?? 0)
                    + ($item->tunjangan_jabatan ?? 0)
                    + (($item->lembur ?? 0) + ($item->lembur_libur ?? 0))
                    + ($item->tunjangan_kebudayaan ?? 0)
                    + ($item->transport ?? 0)
                    + ($item->uang_makan ?? 0)
                    + ($item->fee_sharing ?? 0)
                    + ($item->insentif ?? 0)
                    + ($item->inov_reward ?? 0);

                foreach ($tunjanganArray as $t) {
                    $pendapatan += (int) ($t['nominal'] ?? 0);
                }

                $potongan = ($item->izin ?? 0);

                $excludePotongan = ['pph 21', 'pph21', 'potongan kebudayaan'];

                foreach ($potonganArray as $p) {
                    if (in_array(strtolower($p['nama'] ?? ''), $excludePotongan)) {
                        continue;
                    }
                    $potongan += (int) ($p['nominal'] ?? 0);
                }

                return $pendapatan - $potongan;
            });
        };

        $this->total_gaji = $hitungGaji(
            PayrollModel::whereIn('karyawan_id', $karyawanIds)
                ->where('periode', $this->periode)
                ->where('titip', 0)
                ->get()
        );

        $this->total_gaji_titip = $hitungGaji(
            PayrollModel::whereIn('karyawan_id', $karyawanIds)
                ->where('periode', $this->periode)
                ->where('titip', 1)
                ->get()
        );

        $this->bpjs_kes_pt = PayrollModel::whereIn('karyawan_id', $karyawanIds)
            ->where('periode', $this->periode)
            ->sum('bpjs_perusahaan');

        $this->bpjs_jht_pt = PayrollModel::whereIn('karyawan_id', $karyawanIds)
            ->where('periode', $this->periode)
            ->sum('bpjs_jht_perusahaan');

        $this->potongan_terlambat = PayrollModel::whereIn('karyawan_id', $karyawanIds)
            ->where('periode', $this->periode)
            ->sum('terlambat');

        $this->potongan_terlambat_titip = PayrollModel::whereIn('karyawan_id', $karyawanIds)
            ->where('periode', $this->periode)
            ->where('titip', 1)
            ->sum('terlambat');
    }

    public function render()
    {
        return view('livewire.dashboard-payroll');
    }
}
