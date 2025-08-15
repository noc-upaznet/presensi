<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\M_Presensi;
use Livewire\WithPagination;
use App\Models\M_DataKaryawan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class RiwayatPresensiStaff extends Component
{
    use WithPagination;

    public function approve($id)
    {
        $presensi = M_Presensi::findOrFail($id);
        $presensi->approve = '1';
        $presensi->save();

        $this->dispatch('swal', params: [
            'title' => 'Presensi Approved',
            'icon' => 'success',
            'text' => 'Presensi has been approved successfully'
        ]);
    }

    public function reject($id)
    {
        $presensi = M_Presensi::findOrFail($id);
        $presensi->approve = '2';
        $presensi->save();

        $this->dispatch('swal', params: [
            'title' => 'Presensi Rejected',
            'icon' => 'error',
            'text' => 'Presensi has been rejected successfully'
        ]);
    }
    public function render()
    {
        $userId = Auth::id();

        // Ambil data karyawan dari user yang login
        $karyawan = M_DataKaryawan::where('user_id', $userId)->first();
        // dd($karyawan);

        $divisi = $karyawan->divisi;
        // dd($divisi);
        $karyawanId = $karyawan->id;

        $entitasNama = $karyawan->entitas;
        // dd($entitasNama);

        $datas = M_Presensi::with('getUser')
            // ->where('lokasi_lock', 0)
            ->where('user_id', '!=', $karyawanId)
            ->whereHas('getUser', function ($query) use ($divisi, $entitasNama) {
                $query->where('divisi', $divisi)
                    ->where('entitas', $entitasNama);
            })
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.riwayat-presensi-staff', [
            'datas' => $datas
        ]);
    }



}
