<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PayrollModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class SlipGaji extends Component
{
    public function downloadSlip($id)
    {
        return Redirect::route('slip-gaji.download', ['id' => $id]);
    }

    public function acceptPayroll($id)
    {
        $payroll = PayrollModel::find($id);
        if ($payroll) {
            $payroll->accepted = 1; // Set accepted to true (1)
            $payroll->save();
            $this->dispatch('swal', params: [
                'title' => 'Slip Accepted',
                'icon' => 'success',
                'text' => 'Data has been updated successfully'
            ]);
        } else {
            $this->dispatch('swal', params: [
                'title' => 'Error',
                'icon' => 'error',
                'text' => 'Payroll record not found'
            ]);
        }
    }

    public function render()
    {
        $data = PayrollModel::with('getKaryawan')
            ->whereHas('getKaryawan', function ($query) {
            $query->where('user_id', Auth::id());
            })
            ->where('published', 1)
            ->orderBy('created_at', 'desc')
            ->get();
            // dd($data);
        return view('livewire.slip-gaji', [
            'data' => $data
        ]);
    }
}
