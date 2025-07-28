<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\PayrollModel;
use App\Models\M_DataKaryawan;

class ModalPayroll extends Component
{
    public $search = '';
    public function render()
    {
        $bulanIni = Carbon::now()->format('Y-m');

        $existingKaryawanIds = PayrollModel::where('created_at', 'like', $bulanIni.'%')
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
