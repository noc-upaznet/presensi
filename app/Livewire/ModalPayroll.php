<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\PayrollModel;
use App\Models\M_DataKaryawan;

class ModalPayroll extends Component
{
    public function render()
    {
        $bulanIni = Carbon::now()->format('Y-m');

        $existingKaryawanIds = PayrollModel::where('created_at', 'like', $bulanIni.'%')
            ->pluck('karyawan_id');

        $data = M_DataKaryawan::whereNotIn('id', $existingKaryawanIds)->get();

        return view('livewire.modal-payroll', [
            'data' => $data
        ]);
    }
}
