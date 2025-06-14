<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\M_Presensi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class RiwayatPresensi extends Component
{
    public $editId;
    public $statusList = [
        0 => 'Tepat Waktu',
        1 => 'Terlambat',
        2 => 'Dispensasi',
    ];
    public $status;

    public function mount()
    {
        // $this->statusList = M_Presensi::select('status')->distinct()->pluck('status')->toArray();
    }

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

    public function delete($id)
    {
        $jadwal = M_Presensi::findOrFail(Crypt::decrypt($id));
        // dd($jadwal);
        $jadwal->delete();
        $this->dispatch('swal', params: [
            'title' => 'Data Deleted',
            'icon' => 'success',
            'text' => 'Data has been deleted successfully'
        ]);
        $this->dispatch('modal-confirm-delete', action: 'show');
    }

    public function confirmHapusPresensi($id)
    {
        $decryptedId = Crypt::decrypt($id);
        $this->editId = $decryptedId;
        // dd($decryptedId);
        $data = M_Presensi::find($decryptedId);
        // $this->lokasi_id = $lokasi->id;
        // $this->nama_lokasi = $lokasi->nama;
    }

    public function render()
    {
        if (Auth::user()->role == 'admin' || Auth::user()->role == 'spv' || Auth::user()->role == 'hr') {
            // admin dan hr
            $datas = M_Presensi::with('getUser')
                ->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->orderBy('created_at', 'desc')
                ->get();
        } elseif (Auth::user()->role == 'user') {
            // user
            $datas = M_Presensi::where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->get();
        }
        // dd($dataPresensi);
        return view('livewire.riwayat-presensi', [
            'datas' => $datas
        ]);
    }
}
