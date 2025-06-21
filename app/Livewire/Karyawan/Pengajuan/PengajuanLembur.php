<?php

namespace App\Livewire\Karyawan\Pengajuan;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\M_Jadwal;
use App\Models\M_Lembur;
use Illuminate\Support\Facades\Auth;

class PengajuanLembur extends Component
{
    protected $listeners = ['refreshTable' => 'refresh'];
    public $filterPengajuan = '';
    public $filterBulan = '';
    public $status = [];

    public function showAdd()
    {
        $this->dispatch('modalTambahPengajuanLembur', action: 'show');

    }

    public function updateStatus($id, $status = null)
    {
        $pengajuan = M_Lembur::find($id);

        if (!$pengajuan) {
            return;
        }

        $userRole = Auth::user()->role;

        // Update approve field berdasarkan role dan status
        if ($userRole === 'spv') {
            if ($status == 1) {
                $pengajuan->approve_spv = 1;
            } elseif ($status == 2) {
                $pengajuan->approve_spv = 2;
                $pengajuan->status = 2; // langsung ditolak jika SPV tolak
            }
        } elseif ($userRole === 'hr') {
            if ($pengajuan->approve_spv == 1) {
                if ($status == 1) {
                    $pengajuan->approve_hr = 1;
                    $pengajuan->status = 1; // misal set status jadi diterima penuh
                } elseif ($status == 2) {
                    $pengajuan->approve_hr = 2;
                    $pengajuan->status = 2;
                }
            } elseif ($pengajuan->approve_spv == 2) {
                $pengajuan->status = 2;
                    $this->dispatch('swal', params: [
                        'title' => 'Gagal Menyimpan',
                        'icon' => 'error',
                        'text' => 'SPV sudah menolak pengajuan ini.'
                    ]);
                    return;
            } else {
                $this->dispatch('swal', params: [
                    'title' => 'Gagal Menyimpan',
                    'icon' => 'error',
                    'text' => 'Pengajuan belum disetujui oleh SPV.'
                ]);
                return;
            }
        } else {
            return;
        }

        // Cek jika kedua approver sudah menyetujui, maka status jadi 1
        if ($pengajuan->approve_spv == 1 && $pengajuan->approve_hr == 1) {
            $pengajuan->status = 1;
        }

        $pengajuan->save();

        // Jika status jadi 1, update jadwal
        // if ($pengajuan->status == 1) {
        //     $tanggal = \Carbon\Carbon::parse($pengajuan->tanggal);
        //     $hari = 'd' . $tanggal->day;
        //     $bulanTahun = $tanggal->format('Y-m');

        //     $jadwal = M_Jadwal::where('user_id', $pengajuan->user_id)
        //         ->where('bulan_tahun', $bulanTahun)
        //         ->first();

        //     if ($jadwal) {
        //         $pengajuan->jadwal_sebelumnya = $jadwal->$hari;
        //         $pengajuan->save();

        //         $jadwal->$hari = $pengajuan->shift_id;
        //         $jadwal->save();
        //     } else {
        //         $pengajuan->jadwal_sebelumnya = null;
        //         $pengajuan->save();

        //         $jadwalBaru = new M_Jadwal([
        //             'user_id' => $pengajuan->user_id,
        //             'bulan_tahun' => $bulanTahun,
        //             $hari => $pengajuan->shift_id,
        //         ]);
        //         $jadwalBaru->save();
        //     }
        // }

        $this->dispatch('swal', params: [
            'title' => 'Status Diperbarui',
            'icon' => 'success',
            'text' => 'Status dan jadwal berhasil diperbarui.'
        ]);

        $this->dispatch('refresh');
    }

    public function render()
    {
        $query = M_Lembur::with(['getUser']);

        // Role: Admin atau selain 'user'
        if (Auth::user()->role !== 'user') {
            // Tidak ada filter user_id
        }
        // Role: user
        elseif (Auth::user()->role === 'user') {
            $query->where('user_id', Auth::id());
        }

        // Filter Status
        if (in_array($this->filterPengajuan, ['0', '1', '2'], true)) {
            $query->where('status', (int) $this->filterPengajuan);
        }

        // Filter Bulan
        if (!empty($this->filterBulan)) {
            $bulan = date('m', strtotime($this->filterBulan));
            $tahun = date('Y', strtotime($this->filterBulan));
            $query->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
        }

        // $pengajuan = $query->latest()->get();
        

        if (Auth::user()->role == 'admin' || Auth::user()->role == 'spv' || Auth::user()->role == 'hr') {
            // admin dan hr
            $pengajuanLembur = $query->latest()->get();
        } elseif (Auth::user()->role == 'user') {
            // user
            $pengajuanLembur = M_Lembur::where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->get();
        }
        return view('livewire.karyawan.pengajuan.pengajuan-lembur', [
            'pengajuanLembur' => $pengajuanLembur,
        ]);
    }
}
