<?php

namespace App\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Gamifikasi extends Component
{
    private function countKaryawan()
    {
        $entitas = session('selected_entitas', 'UHO');
        return DB::table('data_karyawan')
            ->where('data_karyawan.entitas', session('selected_entitas'))
            ->where('divisi', 'TEKNISI')
            ->where('level', 'STAFF')
            ->count();
    }

    private function hitungTepatWaktu()
    {
        return DB::table('data_karyawan')
            ->leftJoin('presensi', 'presensi.user_id', '=', 'data_karyawan.id')
            ->leftJoin('jadwal', function ($join) {
                $join->on('jadwal.karyawan_id', '=', 'data_karyawan.id')
                    ->whereRaw('DATE_FORMAT(presensi.tanggal,"%Y-%m") = jadwal.bulan_tahun');
            })
            ->leftJoin('shift', 'shift.id', '=', DB::raw("
            CASE DAY(presensi.tanggal)
                WHEN 1 THEN jadwal.d1
                WHEN 2 THEN jadwal.d2
                WHEN 3 THEN jadwal.d3
                WHEN 4 THEN jadwal.d4
                WHEN 5 THEN jadwal.d5
                WHEN 6 THEN jadwal.d6
                WHEN 7 THEN jadwal.d7
                WHEN 8 THEN jadwal.d8
                WHEN 9 THEN jadwal.d9
                WHEN 10 THEN jadwal.d10
                WHEN 11 THEN jadwal.d11
                WHEN 12 THEN jadwal.d12
                WHEN 13 THEN jadwal.d13
                WHEN 14 THEN jadwal.d14
                WHEN 15 THEN jadwal.d15
                WHEN 16 THEN jadwal.d16
                WHEN 17 THEN jadwal.d17
                WHEN 18 THEN jadwal.d18
                WHEN 19 THEN jadwal.d19
                WHEN 20 THEN jadwal.d20
                WHEN 21 THEN jadwal.d21
                WHEN 22 THEN jadwal.d22
                WHEN 23 THEN jadwal.d23
                WHEN 24 THEN jadwal.d24
                WHEN 25 THEN jadwal.d25
                WHEN 26 THEN jadwal.d26
                WHEN 27 THEN jadwal.d27
                WHEN 28 THEN jadwal.d28
                WHEN 29 THEN jadwal.d29
                WHEN 30 THEN jadwal.d30
                WHEN 31 THEN jadwal.d31
            END
        "))
            ->where('data_karyawan.entitas', session('selected_entitas'))
            ->where('data_karyawan.divisi', 'TEKNISI')
            ->where('data_karyawan.level', 'STAFF')
            ->selectRaw("
            data_karyawan.id as user_id,
            data_karyawan.nama_karyawan,
            SUM(
                CASE 
                    WHEN TIME(presensi.clock_in) <= ADDTIME(shift.jam_masuk,'-00:15:00')
                    THEN 1 ELSE 0
                END
            ) as total_tepat_waktu
        ")
            ->groupBy('data_karyawan.id', 'data_karyawan.nama_karyawan')
            ->orderByDesc('total_tepat_waktu')
            ->get();
    }

    public function render()
    {
        return view('livewire.gamifikasi', [
            'data' => $this->hitungTepatWaktu(),
            'totalKaryawan' => $this->countKaryawan()
        ]);
    }
}
