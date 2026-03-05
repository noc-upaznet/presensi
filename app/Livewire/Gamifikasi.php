<?php

namespace App\Livewire;

use App\Models\M_DataKaryawan;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Gamifikasi extends Component
{
    public $editId;
    public $poin;

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
            data_karyawan.id,
            data_karyawan.nama_karyawan,
            data_karyawan.poin,
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

    private function getKaryawan()
    {
        return DB::table('data_karyawan')
            ->where('entitas', session('selected_entitas'))
            ->where('divisi', 'TEKNISI')
            ->where('level', 'STAFF')
            ->get();
    }

    public function showEditModal($id)
    {
        $decryptedId = Crypt::decrypt($id);
        $this->editId = $decryptedId;

        $data = M_DataKaryawan::find($decryptedId);
        // dd($data);
        if (!$data) {
            session()->flash('error', 'Data tiket tidak ditemukan!');
            return;
        }

        // $this->poin = $data->poin;
        $this->dispatch('editModal', action: 'show');
    }

    public function updatePoin()
    {
        $this->validate([
            'poin' => 'required|numeric|min:0',
        ]);

        // Ambil data karyawan
        $karyawan = $this->editId
            ? M_DataKaryawan::find($this->editId)
            : $this->karyawan;

        if (!$karyawan) {
            session()->flash('error', 'Data karyawan tidak ditemukan!');
            return;
        }

        // Tambahkan poin ke poin lama
        $poinLama = $karyawan->poin ?? 0;
        $poinBaru = $poinLama + $this->poin;

        $karyawan->update([
            'poin' => $poinBaru,
        ]);

        $this->reset('poin');

        $this->dispatch('editModal', action: 'hide');

        $this->dispatch('swal', params: [
            'title' => 'Poin Berhasil Ditambahkan',
            'icon' => 'success',
            'text' => "Poin bertambah, Total sekarang: $poinBaru"
        ]);
    }

    public function render()
    {
        return view('livewire.gamifikasi', [
            'data' => $this->hitungTepatWaktu(),
            'totalKaryawan' => $this->countKaryawan(),
            'karyawans' => $this->getKaryawan(),
        ]);
    }
}
