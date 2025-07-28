<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\M_Presensi;
use App\Models\M_DataKaryawan;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class RiwayatPresensiStaff extends Component
{
    // public $approve;
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

        $divisi = $karyawan->divisi;
        $karyawanId = $karyawan->id; // id dari tabel data_karyawan

        $datas = M_Presensi::with('getUser')
            ->where('lokasi_lock', 0)
            ->where('user_id', '!=', $karyawanId) // user_id pada presensi = karyawan_id
            ->whereHas('getUser', function ($query) use ($divisi) {
                $query->where('divisi', $divisi);
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
