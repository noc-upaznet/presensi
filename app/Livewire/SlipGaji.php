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
