<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\PayrollModel;
use App\Models\M_DataKaryawan;

class ModalPayroll extends Component
{
    public $search = '';
    public $periode;
    public $karyawan_id;

    #[On('modalPayroll')]
    public function handleModal($action, $periode, $karyawanId = null)
    {
        if ($action === 'show') {
            $this->periode = $periode;

            if ($karyawanId) {
                // Simpan karyawan ID di property Livewire
                $this->karyawan_id = decrypt($karyawanId); // jika dikirim terenkripsi

                // Load data karyawan langsung
                $this->loadDataKaryawan($this->karyawan_id);
            }
        }
    }

    #[On('modalPayrollEks')]
    public function handleModalEks($action, $periode)
    {
        // dd($action, $periode);
        if ($action === 'show') {
            $this->periode = $periode;
            // dd($this->periode);
        }
    }

    public function redirectToSlip($id, $periode)
    {
        // dd(decrypt($id), $periode);
        // pecah periode (formatnya "2025-08")
        $carbon = \Carbon\Carbon::parse($periode);

        $month = $carbon->format('n'); // angka bulan tanpa leading zero
        $year  = $carbon->format('Y');

        // redirect ke route dengan parameter id, month, year
        return redirect()->route('create-slip-gaji', [
            'id'    => $id,
            'month' => $month,
            'year'  => $year,
        ]);
    }
    
    public function render()
    {
        $periode = $this->periode ?? Carbon::now()->format('Y-m');
        $existingKaryawanIds = PayrollModel::where('periode', $periode)
            ->pluck('karyawan_id');

        $selectedEntitas = session('selected_entitas', 'UHO');

        $data = M_DataKaryawan::where('entitas', $selectedEntitas)
            ->whereNotIn('id', $existingKaryawanIds)
            ->when($this->search, function ($query) {
                $query->where('nama_karyawan', 'like', '%' . $this->search . '%');
            })
            ->orderByDesc('id')
            ->get();

        $dataEks = M_DataKaryawan::where('entitas', '!=', $selectedEntitas)
            ->whereNotIn('id', $existingKaryawanIds)
            ->when($this->search, function ($query) {
                $query->where('nama_karyawan', 'like', '%' . $this->search . '%');
            })
            ->orderByDesc('id')
            ->get();

        return view('livewire.modal-payroll', [
            'data' => $data,
            'dataEks' => $dataEks,
        ]);
    }
}
