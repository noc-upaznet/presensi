<?php

namespace App\Livewire\Karyawan\Pengajuan;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\M_Jadwal;
use App\Models\M_Pengajuan;
use App\Models\M_JadwalShift;
use App\Models\M_DataKaryawan;
use App\Livewire\Forms\PengajuanForm;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;

class ModalPengajuan extends Component
{
    public PengajuanForm $form;
    public $karyawans;
    public $shifts;
    public $nama_karyawan;
    public $pengajuan;
    public $tanggal;
    public $keterangan;
    public $file;
    public $detail;

    protected $listeners = ['refreshTable' => 'refresh'];

    public function mount()
    {
        $this->shifts = M_JadwalShift::whereIn('nama_shift', 
        [
            'Izin', 
            'Cuti', 
            'Izin Setengah Hari',
        ])->orderBy('nama_shift')->get();
    }

    public function store()
    {
        $this->form->validate();

        $this->validate([
            'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5048',
        ]);

        $path = null;
        if ($this->file) {
            $path = $this->file->store('file', 'public');
        }
        // dd(auth()->id());
        $data = [
            'user_id' => Auth::id(),
            'shift_id' => $this->form->pengajuan,
            'tanggal' => $this->form->tanggal,
            'keterangan' => $this->form->keterangan,
            'file' => $path ? str_replace('public/', 'storage/', $path) : null,
            'satatus' => 0, // Status default 0 (pending)
        ];
        // dd($data);

        // Simpan data ke database
        M_Pengajuan::create($data);

        // Reset input
        $this->form->reset();

        $this->dispatch('swal', params: [
            'title' => 'Data Saved',
            'icon' => 'success',
            'text' => 'Data has been saved successfully'
        ]);

        // Tutup modal
        $this->dispatch('modalTambahPengajuan', action: 'hide');
        $this->dispatch('refresh');
    }

    public function delete($id)
    {
        $pengajuan = M_Pengajuan::findOrFail(Crypt::decrypt($id));
        // dd($pengajuan);
        if (!$pengajuan) return;

        if ($pengajuan->status === 1) {
            $tanggal = \Carbon\Carbon::parse($pengajuan->tanggal);
            $hari = 'd' . $tanggal->day;
            $bulanTahun = $tanggal->format('Y-m');

            $jadwal = M_Jadwal::where('user_id', $pengajuan->user_id)
                ->where('bulan_tahun', $bulanTahun)
                ->first();

            if ($jadwal) {
                $jadwal->$hari = $pengajuan->jadwal_sebelumnya;
                $jadwal->save();
            }
        }

        $pengajuan->delete();

        $this->dispatch('swal', params: [
            'title' => 'Pengajuan Dihapus',
            'icon' => 'success',
            'text' => 'Data pengajuan dihapus dan jadwal dikembalikan.'
        ]);

        $this->dispatch('modal-confirm-delete', action: 'hide');
        $this->dispatch('refresh');
    }

    public function render()
    {
        return view('livewire.karyawan.pengajuan.modal-pengajuan');
    }
}
