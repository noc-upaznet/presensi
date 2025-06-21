<?php

namespace App\Livewire\Navigation;

use Livewire\Component;
use App\Models\M_Lembur;
use App\Models\M_Pengajuan;
use Illuminate\Support\Facades\Auth;

class SideNavigation extends Component
{
    public function render()
    {
        $user = Auth::user();
        $countPengajuan = 0;
        $countLembur = 0;

        if ($user) {
            if ($user->role === 'spv') {
                // Untuk pengajuan cuti/izin
                $countPengajuan = M_Pengajuan::whereNull('approve_spv')
                    ->where('status', 0)
                    ->count();

                // Untuk pengajuan lembur
                $countLembur = M_Lembur::whereNull('approve_spv')
                    ->where('status', 0)
                    ->count();

            } elseif ($user->role === 'hr') {
                // Untuk pengajuan cuti/izin
                $countPengajuan = M_Pengajuan::where('approve_spv', 1)
                    ->whereNull('approve_hr')
                    ->where('status', 0)
                    ->count();

                // Untuk pengajuan lembur
                $countLembur = M_Lembur::where('approve_spv', 1)
                    ->whereNull('approve_hr')
                    ->where('status', 0)
                    ->count();
            }
        }

        return view('livewire.navigation.side-navigation', [
            'pengajuanMenungguCount' => $countPengajuan,
            'lemburMenungguCount' => $countLembur,
        ]);
    }
}
