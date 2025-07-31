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
    public $editId;
    public $status;

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

    public $statusList = [
        0 => 'Tepat Waktu',
        1 => 'Terlambat',
        2 => 'Dispensasi',
    ];

    public function showModal($id)
    {
        $decryptedId = Crypt::decrypt($id);
        $this->editId = $decryptedId;

        $data = M_Presensi::find($decryptedId);
        // dd($data);
        if (!$data) {
            session()->flash('error', 'Data tiket tidak ditemukan!');
            return;
        }

        $this->status = $data->status;
        $this->dispatch('editModal', action: 'show');
    }

    public function updateStatus()
    {
        // Cari data berdasarkan ID yang sudah didekripsi sebelumnya
        $data = M_Presensi::find($this->editId);

        if (!$data) {
            session()->flash('error', 'Data presensi tidak ditemukan!');
            return;
        }

        // Update field status dengan nilai dari form
        $data->status = $this->status;
        $data->save();

        // Reset form jika perlu
        $this->reset(['editId', 'status']);

        // Tutup modal
        $this->dispatch('editModal', action: 'hide');
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

        $entitasNama = session('selected_entitas', 'UHO');

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
