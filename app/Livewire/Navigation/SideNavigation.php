<?php

namespace App\Livewire\Navigation;

use Livewire\Component;
use App\Models\M_Lembur;
use App\Models\M_Entitas;
use App\Models\M_Presensi;
use App\Models\M_Pengajuan;
use App\Models\M_DataKaryawan;
use Carbon\Carbon;
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
            // 🔹 SPV
            if ($user->hasRole('spv')) {
                $dataKaryawan = M_DataKaryawan::where('user_id', $user->id)->first();
                $entitas = $dataKaryawan?->entitas;
                $entitasModel = M_Entitas::where('nama', $entitas)->first();
                $entitasIdSaatIni = $entitasModel?->nama;
                $divisi = $dataKaryawan->divisi;

                if ($dataKaryawan) {
                    $karyawanIds = M_DataKaryawan::where('divisi', $dataKaryawan->divisi)
                        ->where('entitas', $dataKaryawan->entitas)
                        ->pluck('id');

                    // pengajuan cuti/izin
                    $countPengajuan = M_Pengajuan::whereNull('approve_spv')
                        ->where('status', 0)
                        ->whereIn('karyawan_id', $karyawanIds)
                        ->where('karyawan_id', '!=', $dataKaryawan->id)
                        ->whereHas('getKaryawan', fn($q) => $q->where('entitas', $entitasIdSaatIni))
                        ->whereMonth('tanggal', Carbon::now()->month)
                        ->whereYear('tanggal', Carbon::now()->year)
                        ->count();

                    // Lembur
                    $countLembur = M_Lembur::whereNull('approve_spv')
                        ->where('status', 0)
                        ->whereIn('karyawan_id', $karyawanIds)
                        ->where('karyawan_id', '!=', $dataKaryawan->id)
                        ->whereHas('getKaryawan', fn($q) => $q->where('entitas', $entitasIdSaatIni))
                        ->whereMonth('tanggal', Carbon::now()->month)
                        ->whereYear('tanggal', Carbon::now()->year)
                        ->count();
                    if ($divisi == 'NOC'){
                        $countPresensiStaff = M_Presensi::where('lokasi_lock', 0)
                            ->where('approve', 0)
                            ->where('user_id', '!=', $dataKaryawan->id)
                            ->whereHas('getKaryawan', function ($q) use ($divisi) {
                                $q->where('divisi', $divisi);
                            })
                            ->count();
                    } else {
                        $countPresensiStaff = M_Presensi::where('lokasi_lock', 0)
                            ->where('approve', 0)
                            ->where('user_id', '!=', $dataKaryawan->id)
                            ->whereHas('getKaryawan', fn($q) => 
                                $q->where('entitas', $entitasIdSaatIni)
                                ->where('divisi', $dataKaryawan->divisi)
                            )
                            ->whereMonth('tanggal', Carbon::now()->month)
                            ->whereYear('tanggal', Carbon::now()->year)
                            ->count();
                    }
                        // dd($countPresensiStaff);
                } else {
                    $countPengajuan = 0;
                    $countLembur = 0;
                    $countPresensiStaff = 0;
                }

            // 🔹 HR
            } elseif ($user->hasRole('hr')) {
                $dataKaryawan = M_DataKaryawan::where('user_id', $user->id)->first();
                $entitas = $dataKaryawan?->entitas;
                
                if ($dataKaryawan) {
                    // Pengajuan
                    $countPengajuan = M_Pengajuan::where(function ($q) {
                            $q->where('approve_spv', 1)
                            ->orWhereNull('approve_spv');
                        })
                        ->whereNull('approve_hr')
                        ->where('status', 0)
                        ->where('karyawan_id', '!=', $dataKaryawan->id)
                        ->whereHas('getKaryawan', fn($q) => $q->where('entitas', $entitas))
                        ->whereMonth('tanggal', Carbon::now()->month)
                        ->whereYear('tanggal', Carbon::now()->year)
                        ->count();

                    // Lembur
                    $countLembur = M_Lembur::where(function ($q) {
                            $q->where('approve_spv', 1)
                            ->orWhereNull('approve_spv');
                        })
                        ->whereNull('approve_hr')
                        ->where('status', 0)
                        ->where('karyawan_id', '!=', $dataKaryawan->id)
                        ->whereHas('getKaryawan', fn($q) => $q->where('entitas', $entitas))
                        ->whereMonth('tanggal', Carbon::now()->month)
                        ->whereYear('tanggal', Carbon::now()->year)
                        ->count();
                } else {
                    $countPengajuan = 0;
                    $countLembur = 0;
                }

            // 🔹 Admin
            } elseif ($user->hasRole('admin')) {
                $entitas = session('selected_entitas', 'UHO');
                $entitasModel = M_Entitas::where('nama', $entitas)->first();
                $entitasIdSaatIni = $entitasModel?->nama;

                $countPengajuan = M_Pengajuan::whereNull('approve_admin')
                    ->where('status', 0)
                    ->whereMonth('tanggal', now()->month)
                    ->whereYear('tanggal', now()->year)
                    ->whereHas('getKaryawan', function ($q) use ($entitasIdSaatIni) {
                        $q->where('entitas', $entitasIdSaatIni);
                    })
                    ->whereMonth('tanggal', Carbon::now()->month)
                    ->whereYear('tanggal', Carbon::now()->year)
                    ->count();

                $countLembur = M_Lembur::whereNull('approve_admin')
                    ->where('status', 0)
                    ->whereMonth('tanggal', now()->month)
                    ->whereYear('tanggal', now()->year)
                    ->whereHas('getKaryawan', function ($q) use ($entitasIdSaatIni) {
                        $q->where('entitas', $entitasIdSaatIni);
                    })
                    ->whereMonth('tanggal', Carbon::now()->month)
                    ->whereYear('tanggal', Carbon::now()->year)
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
