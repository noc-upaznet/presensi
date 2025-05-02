<?php

namespace App\Livewire\Karyawan\Pengajuan;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\M_Jadwal;
use App\Models\M_Pengajuan;
use Illuminate\Support\Facades\Crypt;

class Pengajuan extends Component
{
    protected $listeners = ['refreshTable' => 'refresh'];
    public function showAdd()
    {
        // Dispatch event ke modal
        $this->dispatch('modalTambahPengajuan', action: 'show');
    }
    
    public function updateStatus($id, $status)
    {
        $pengajuan = M_Pengajuan::find($id);

        if (!$pengajuan) {
            return;
        }

        $pengajuan->status = $status;
        $pengajuan->save();

        if ($status === 1) {
            // Ambil jadwal berdasarkan karyawan dan bulan dari tanggal pengajuan
            $tanggal = \Carbon\Carbon::parse($pengajuan->tanggal);
            // dd($tanggal);
            $hari = 'd' . $tanggal->day;
            $bulanTahun = $tanggal->format('Y-m');

            $jadwal = M_Jadwal::where('id_karyawan', $pengajuan->karyawan_id)
                ->where('bulan_tahun', $bulanTahun)
                ->first();

            if ($jadwal) {
                // Simpan shift sebelumnya
                $pengajuan->jadwal_sebelumnya = $jadwal->$hari;
                $pengajuan->save();
    
                $jadwal->$hari = $pengajuan->shift_id;
                $jadwal->save();
            } else {
                // Belum ada jadwal, jadwal sebelumnya = null
                $pengajuan->jadwal_sebelumnya = null;
                $pengajuan->save();
    
                $jadwalBaru = new M_Jadwal([
                    'id_karyawan' => $pengajuan->karyawan_id,
                    'bulan_tahun' => $bulanTahun,
                    $hari => $pengajuan->shift_id,
                ]);
                $jadwalBaru->save();
            }
        }


        $this->dispatch('swal', params: [
            'title' => 'Status & Jadwal Updated',
            'icon' => 'success',
            'text' => 'Status & Jadwal has been updated successfully'
        ]);

        $this->dispatch('refresh');
    }

   

    public function render()
    {
        $pengajuan = M_Pengajuan::with(['getKaryawan', 'getShift'])->latest()->get();
        return view('livewire.karyawan.pengajuan.pengajuan', [
            'pengajuans' => $pengajuan,
        ]);
    }
}
