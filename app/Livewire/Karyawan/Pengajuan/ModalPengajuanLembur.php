<?php

namespace App\Livewire\Karyawan\Pengajuan;

use App\Models\User;
use Livewire\Component;
use App\Models\M_Lembur;
use App\Models\M_Pengajuan;
use Livewire\WithFileUploads;
use App\Events\PengajuanBaruEvent;
use App\Livewire\Forms\LemburForm;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class ModalPengajuanLembur extends Component
{
    use WithFileUploads;
    public LemburForm $form;

    public $karyawans;
    public $file_bukti;
    protected $listeners = ['refreshTable' => 'refresh'];

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['form.waktu_mulai', 'form.waktu_akhir'])) {
            $this->hitungJamLembur();
        }
    }

    public function hitungJamLembur()
    {
        $mulai = strtotime($this->form->waktu_mulai);
        $akhir = strtotime($this->form->waktu_akhir);
        if ($mulai !== false && $akhir !== false) {
            $hasilLembur = ($akhir - $mulai) / 3600; // hasil dalam jam
            if ($hasilLembur < 0) {
                $hasilLembur += 24;
            }
        } else {
            $hasilLembur = 0;
        }
        $this->form->total_jam = $hasilLembur;
    }

    public function store()
    {
        $this->form->validate();

        $this->validate([
            'file_bukti' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $path = null;
        if ($this->file_bukti) {
            $path = $this->file_bukti->store('file-lembur', 'public');
        }

        $data = [
            'user_id' => Auth::id(),
            'tanggal' => $this->form->tanggal,
            'jenis' => $this->form->jenis,
            'keterangan' => $this->form->keterangan,
            'waktu_mulai' => $this->form->waktu_mulai,
            'waktu_akhir' => $this->form->waktu_akhir,
            'total_jam' => $this->form->total_jam,
            'file_bukti' => $path ? str_replace('public/', 'storage/', $path) : null,
            'satatus' => 0,
        ];
        // dd($data);

        // Simpan data ke database
        // M_Pengajuan::create($data);
        $pengajuan = M_Lembur::create($data)->load('getUser');

        // Reset input
        $this->form->reset();

        $this->dispatch('swal', params: [
            'title' => 'Pengajuan Baru',
            'icon' => 'success',
            'text' => 'Pengajuan baru telah diajukan.'
        ]);

        // Tutup modal
        $this->dispatch('modalTambahPengajuanLembur', action: 'hide');
        $this->dispatch('refresh');
        
    }

    public function delete($id)
    {
        $pengajuan = M_Lembur::findOrFail(Crypt::decrypt($id));
        // dd($pengajuan);
        if (!$pengajuan) return;

        // if ($pengajuan->status === 1) {
        //     $tanggal = \Carbon\Carbon::parse($pengajuan->tanggal);
        //     $hari = 'd' . $tanggal->day;
        //     $bulanTahun = $tanggal->format('Y-m');

        //     $jadwal = M_Jadwal::where('id_karyawan', $pengajuan->karyawan_id)
        //         ->where('bulan_tahun', $bulanTahun)
        //         ->first();

        //     if ($jadwal) {
        //         $jadwal->$hari = $pengajuan->jadwal_sebelumnya;
        //         $jadwal->save();
        //     }
        // }

        $pengajuan->delete();

        $this->dispatch('swal', params: [
            'title' => 'Pengajuan Dihapus',
            'icon' => 'success',
            'text' => 'Data pengajuan dihapus.'
        ]);

        $this->dispatch('modal-confirm-delete', action: 'hide');
        $this->dispatch('refresh');
    }
    
    public function render()
    {
        return view('livewire.karyawan.pengajuan.modal-pengajuan-lembur');
    }
}
