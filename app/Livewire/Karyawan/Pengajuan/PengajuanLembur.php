<?php

namespace App\Livewire\Karyawan\Pengajuan;

use Livewire\Component;
use App\Models\M_Lembur;

class PengajuanLembur extends Component
{
    protected $listeners = ['refreshTable' => 'refresh'];

    public function showAdd()
    {
        $this->dispatch('modalTambahPengajuanLembur', action: 'show');

    }

    public function updateStatus($id, $status)
    {
        $pengajuan = M_Lembur::find($id);

        if (!$pengajuan) {
            return;
        }
        // Update status pengajuan
        $pengajuan->status = $status;
        // dd($pengajuan->status);
        $pengajuan->save();

        // if ($status === 1) {
        //     // Ambil jadwal berdasarkan karyawan dan bulan dari tanggal pengajuan
        //     $tanggal = \Carbon\Carbon::parse($pengajuan->tanggal);
        //     // dd($tanggal);
        //     $hari = 'd' . $tanggal->day;
        //     $bulanTahun = $tanggal->format('Y-m');

        //     $jadwal = M_Jadwal::where('id_karyawan', $pengajuan->karyawan_id)
        //         ->where('bulan_tahun', $bulanTahun)
        //         ->first();

        //     if ($jadwal) {
        //         // Simpan shift sebelumnya
        //         $pengajuan->jadwal_sebelumnya = $jadwal->$hari;
        //         $pengajuan->save();
    
        //         $jadwal->$hari = $pengajuan->shift_id;
        //         $jadwal->save();
        //     } else {
        //         // Belum ada jadwal, jadwal sebelumnya = null
        //         $pengajuan->jadwal_sebelumnya = null;
        //         $pengajuan->save();
    
        //         $jadwalBaru = new M_Jadwal([
        //             'id_karyawan' => $pengajuan->karyawan_id,
        //             'bulan_tahun' => $bulanTahun,
        //             $hari => $pengajuan->shift_id,
        //         ]);
        //         $jadwalBaru->save();
        //     }
        // }


        $this->dispatch('swal', params: [
            'title' => 'Status Updated',
            'icon' => 'success',
            'text' => 'Status has been updated successfully'
        ]);

        $this->dispatch('refresh');
    }

    public function render()
    {
        // $pengajuanLembur = M_Lembur::with(['getUser'])->latest()->get();
        $pengajuanLembur = M_Lembur::with(['getUser'])
        ->where('user_id', auth()->id())
        ->latest()
        ->get();
        return view('livewire.karyawan.pengajuan.pengajuan-lembur', [
            'pengajuanLembur' => $pengajuanLembur,
        ]);
    }
}
