<?php

namespace App\Livewire\Navigation;

use Livewire\Component;
use App\Models\M_Lembur;
use App\Models\M_Entitas;
use App\Models\M_Presensi;
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
        $countPresensiStaff = 0;

        if ($user) {
            if ($user->current_role === 'spv') {
                $dataKaryawan = M_DataKaryawan::where('user_id', $user->id)->first();
                $entitas = $dataKaryawan->entitas; // default fallback
                $entitasModel = M_Entitas::where('nama', $entitas)->first();
                $entitasIdSaatIni = $entitasModel?->nama;

                if ($dataKaryawan) {
                    $karyawanIds = M_DataKaryawan::where('divisi', $dataKaryawan->divisi)
                        ->where('entitas', $dataKaryawan->entitas)
                        ->pluck('id');

                    // Untuk pengajuan cuti/izin dari karyawan satu divisi (kecuali dia sendiri)
                    $countPengajuan = M_Pengajuan::whereNull('approve_spv')
                        ->where('status', 0)
                        ->whereIn('karyawan_id', $karyawanIds)
                        ->where('karyawan_id', '!=', $dataKaryawan->id)
                        ->whereHas('getKaryawan', function ($q) use ($entitasIdSaatIni) {
                            $q->where('entitas', $entitasIdSaatIni);
                        })
                        ->count();

                    // Untuk pengajuan lembur dari karyawan satu divisi (kecuali dia sendiri)
                    $countLembur = M_Lembur::whereNull('approve_spv')
                        ->where('status', 0)
                        ->whereIn('karyawan_id', $karyawanIds)
                        ->where('karyawan_id', '!=', $dataKaryawan->id)
                        ->whereHas('getKaryawan', function ($q) use ($entitasIdSaatIni) {
                            $q->where('entitas', $entitasIdSaatIni);
                        })
                        ->count();

                    $countPresensiStaff = M_Presensi::where('lokasi_lock', 0)
                        ->where('approve', 0)
                        ->where('user_id', '!=', $dataKaryawan->id)
                        ->whereHas('getKaryawan', function ($q) use ($entitasIdSaatIni) {
                            $q->where('entitas', $entitasIdSaatIni);
                        })
                        ->count();
                        // dd($countPresensiStaff);
                } else {
                    $countPengajuan = 0;
                    $countLembur = 0;
                    $countPresensiStaff = 0;
                }
            } elseif ($user->current_role === 'hr') {
                $dataKaryawan = M_DataKaryawan::where('user_id', $user->id)->first();

                if ($dataKaryawan) {
                    // Pengajuan cuti/izin (hanya yang sudah disetujui SPV, belum disetujui HR, dan bukan milik HR itu sendiri)
                    $countPengajuan = M_Pengajuan::where(function ($q) {
                            $q->where('approve_spv', 1)
                            ->orWhereNull('approve_spv');
                        })
                        ->whereNull('approve_hr')
                        ->where('status', 0)
                        ->where('karyawan_id', '!=', $dataKaryawan->id)
                        ->count();

                    // Pengajuan lembur (sama seperti di atas)
                    $countLembur = M_Lembur::where(function ($q) {
                            $q->where('approve_spv', 1)
                            ->orWhereNull('approve_spv');
                        })
                        ->whereNull('approve_hr')
                        ->where('status', 0)
                        ->where('karyawan_id', '!=', $dataKaryawan->id)
                        ->count();
                } else {
                    $countPengajuan = 0;
                    $countLembur = 0;
                }
            } elseif ($user->current_role === 'admin') {
                $entitas = session('selected_entitas', 'UHO'); // default fallback
                $entitasModel = M_Entitas::where('nama', $entitas)->first();
                $entitasIdSaatIni = $entitasModel?->nama;

                 $countPengajuan = M_Pengajuan::whereNull('approve_admin')
                    ->where('status', 0)
                    ->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->whereHas('getKaryawan', function ($q) use ($entitasIdSaatIni) {
                        $q->where('entitas', $entitasIdSaatIni);
                    })
                    ->count();
                // dd($entitasIdSaatIni);

                // Pengajuan lembur yang belum disetujui admin
                $countLembur = M_Lembur::whereNull('approve_admin')
                    ->where('status', 0)
                    ->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->whereHas('getKaryawan', function ($q) use ($entitasIdSaatIni) {
                        $q->where('entitas', $entitasIdSaatIni);
                    })
                    ->count();
            }
        }


        return view('livewire.navigation.side-navigation', [
            'pengajuanMenungguCount' => $countPengajuan,
            'lemburMenungguCount' => $countLembur,
            'PresensiMenungguCount' => $countPresensiStaff,
        ]);
    }
}
