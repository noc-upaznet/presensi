<?php

namespace App\Livewire\Navigation;

use Livewire\Component;
use App\Models\M_Lembur;
use App\Models\M_Entitas;
use App\Models\M_Presensi;
use App\Models\M_Pengajuan;
use App\Models\M_DataKaryawan;
use App\Models\M_Dispensation;
use App\Models\M_Sharing;
use App\Traits\CutoffPayrollTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class SideNavigation extends Component
{
    use CutoffPayrollTrait;

    public function render()
    {
        $user = Auth::user();
        $countPengajuan = 0;
        $countLembur = 0;
        $countPresensiStaff = 0;
        $countDispensasi = 0;
        $countFeeSharing = 0;

        $cutoff = $this->resolveCutoff(now()->year, now()->month, 'cutoff_25');

        if ($user) {
            // 🔹 SPV
            if ($user->hasRole('spv')) {
                $dataKaryawan = M_DataKaryawan::where('user_id', $user->id)->first();
                $entitas = $dataKaryawan?->entitas;
                $entitasModel = M_Entitas::where('nama', $entitas)->first();
                $entitasIdSaatIni = $entitasModel?->nama;
                $divisi = $dataKaryawan->divisi;
                // dd($entitasIdSaatIni, $divisi);

                if ($dataKaryawan) {
                    $karyawanIds = M_DataKaryawan::where('divisi', $dataKaryawan->divisi)
                        ->where('entitas', $dataKaryawan->entitas)
                        ->pluck('id');

                    // pengajuan cuti/izin
                    if ($divisi == 'NOC') {
                        $countPengajuan = M_Pengajuan::whereNull('approve_spv')
                            ->where('status', 0)
                            ->where('karyawan_id', '!=', $dataKaryawan->id)
                            ->whereHas('getKaryawan', function ($q) use ($divisi, $cutoff) {
                                $q->where('divisi', $divisi)
                                    ->whereBetween('tanggal', [
                                        $cutoff['start'],
                                        $cutoff['end'],
                                    ]);
                            })
                            ->count();
                    } else if ($divisi == 'Finance' && $entitasIdSaatIni == 'UNR') {
                        $countPengajuan = M_Pengajuan::whereNull('approve_spv')
                            ->where('status', 0)
                            ->where('karyawan_id', '!=', $dataKaryawan->id)
                            ->whereHas('getKaryawan', function ($q) use ($divisi, $cutoff) {
                                $q->where(function ($subQ) use ($divisi) {
                                    $subQ->where('divisi', $divisi)
                                        ->where('entitas', 'UNR');
                                })->orWhere('entitas', 'MC');
                                $q->whereBetween('tanggal', [
                                    $cutoff['start'],
                                    $cutoff['end'],
                                ]);
                            })
                            ->count();
                    } else {
                        $countPengajuan = M_Pengajuan::whereNull('approve_spv')
                            ->where('status', 0)
                            ->whereIn('karyawan_id', $karyawanIds)
                            ->where('karyawan_id', '!=', $dataKaryawan->id)
                            ->whereHas('getKaryawan', fn($q) => $q->where('entitas', $entitasIdSaatIni))
                            ->whereBetween('tanggal', [
                                $cutoff['start'],
                                $cutoff['end'],
                            ])
                            ->count();
                    }

                    // Lembur
                    if ($divisi == 'NOC') {
                        $countLembur = M_Lembur::where('approve_spv', 0)
                            ->where('status', 0)
                            ->where('karyawan_id', '!=', $dataKaryawan->id)
                            ->whereHas('getKaryawan', function ($q) use ($divisi, $cutoff) {
                                $q->where('divisi', $divisi)
                                    ->whereBetween('tanggal', [
                                        $cutoff['start'],
                                        $cutoff['end'],
                                    ]);
                            })
                            ->count();
                    } else if ($divisi == 'Finance' && $entitasIdSaatIni == 'UNR') {
                        $countLembur = M_Lembur::whereNull('approve_spv')
                            ->where('status', 0)
                            ->where('karyawan_id', '!=', $dataKaryawan->id)
                            ->whereHas('getKaryawan', function ($q) use ($divisi, $cutoff) {
                                $q->where(function ($subQ) use ($divisi, $cutoff) {
                                    $subQ->where('divisi', $divisi)
                                        ->where('entitas', 'UNR');
                                })->orWhere('entitas', 'MC');
                                $q->whereBetween('tanggal', [
                                    $cutoff['start'],
                                    $cutoff['end'],
                                ]);
                            })
                            ->count();
                        // dd($countLembur);
                    } else {
                        $countLembur = M_Lembur::whereNull('approve_spv')
                            ->where('status', 0)
                            ->whereIn('karyawan_id', $karyawanIds)
                            ->where('karyawan_id', '!=', $dataKaryawan->id)
                            ->whereHas('getKaryawan', fn($q) => $q->where('entitas', $entitasIdSaatIni))
                            ->whereBetween('tanggal', [
                                $cutoff['start'],
                                $cutoff['end'],
                            ])
                            ->count();
                    }

                    // Presensi Staff
                    if ($divisi == 'NOC') {
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
                            ->whereHas(
                                'getKaryawan',
                                fn($q) =>
                                $q->where('entitas', $entitasIdSaatIni)
                                    ->where('divisi', $dataKaryawan->divisi)
                            )
                            ->whereBetween('tanggal', [
                                $cutoff['start'],
                                $cutoff['end'],
                            ])
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
                $entitas = session('selected_entitas', 'UHO');

                if ($dataKaryawan) {
                    // dd($entitas);
                    // Pengajuan

                    $countPengajuan = M_Pengajuan::where(function ($q) {
                        $q->where('approve_spv', 1)
                            ->orWhereNull('approve_spv');
                    })
                        ->whereNull('approve_hr')
                        ->where('status', 0)
                        ->where('karyawan_id', '!=', $dataKaryawan->id)
                        ->whereHas('getKaryawan', fn($q) => $q->where('entitas', $entitas))
                        ->whereBetween('tanggal', [
                            $cutoff['start'],
                            $cutoff['end'],
                        ])
                        ->count();
                    // dd($countPengajuan);

                    // Lembur
                    $countLembur = M_Lembur::where(function ($q) {
                        $q->where('approve_spv', 1)
                            ->orWhereNull('approve_spv');
                    })
                        ->whereNull('approve_hr')
                        ->where('status', 0)
                        ->where('karyawan_id', '!=', $dataKaryawan->id)
                        ->whereHas('getKaryawan', fn($q) => $q->where('entitas', $entitas))
                        ->whereBetween('tanggal', [
                            $cutoff['start'],
                            $cutoff['end'],
                        ])
                        ->count();

                    $countDispensasi = M_Dispensation::where('approve_hr', 0)
                        ->where('status', 0)
                        ->where('karyawan_id', '!=', $dataKaryawan->id)
                        ->whereHas('getKaryawan', fn($q) => $q->where('entitas', $entitas))
                        ->whereBetween('date', [
                            $cutoff['start'],
                            $cutoff['end'],
                        ])
                        ->count();

                    $countFeeSharing = M_Sharing::where('approve_hr', 0)
                        ->where('status', 0)
                        ->where('karyawan_id', '!=', $dataKaryawan->id)
                        ->whereHas('getKaryawan', fn($q) => $q->where('entitas', $entitas))
                        ->whereBetween('date', [
                            $cutoff['start'],
                            $cutoff['end'],
                        ])
                        ->count();
                    // dd($countDispensasi);
                } else {
                    $countPengajuan = 0;
                    $countLembur = 0;
                    $countDispensasi = 0;
                    $countFeeSharing = 0;
                }

                // 🔹 Admin
            } elseif ($user->hasRole('admin')) {
                $entitas = session('selected_entitas', 'UHO');
                $entitasModel = M_Entitas::where('nama', $entitas)->first();
                $entitasIdSaatIni = $entitasModel?->nama;

                $countPengajuan = M_Pengajuan::whereNull('approve_admin')
                    ->where('status', 0)
                    ->whereBetween('tanggal', [
                        $cutoff['start'],
                        $cutoff['end'],
                    ])
                    ->whereHas('getKaryawan', function ($q) use ($entitasIdSaatIni) {
                        $q->where('entitas', $entitasIdSaatIni);
                    })
                    ->count();
                // dd($countPengajuan);

                $countLembur = M_Lembur::whereNull('approve_admin')
                    ->where('status', 0)
                    ->whereBetween('tanggal', [
                        $cutoff['start'],
                        $cutoff['end'],
                    ])
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
            'DispensasiMenungguCount' => $countDispensasi,
            'FeeSharingMenungguCount' => $countFeeSharing,
        ]);
    }
}
