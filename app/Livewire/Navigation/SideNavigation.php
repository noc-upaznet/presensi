<?php

namespace App\Livewire\Navigation;

use Livewire\Component;
use App\Models\M_Lembur;
use App\Models\M_Pengajuan;
use App\Models\M_DataKaryawan;
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
                $dataKaryawan = M_DataKaryawan::where('user_id', $user->id)->first();

                if ($dataKaryawan) {
                    $karyawanIds = M_DataKaryawan::where('divisi', $dataKaryawan->divisi)
                        ->where('entitas', $dataKaryawan->entitas) // opsional jika perlu filter entitas juga
                        ->pluck('id');

                    // Untuk pengajuan cuti/izin dari karyawan satu divisi
                    $countPengajuan = M_Pengajuan::whereNull('approve_spv')
                        ->where('status', 0)
                        ->whereIn('karyawan_id', $karyawanIds)
                        ->count();

                    // Untuk pengajuan lembur dari karyawan satu divisi
                    $countLembur = M_Lembur::whereNull('approve_spv')
                        ->where('status', 0)
                        ->whereIn('karyawan_id', $karyawanIds)
                        ->count();
                } else {
                    $countPengajuan = 0;
                    $countLembur = 0;
                }

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
