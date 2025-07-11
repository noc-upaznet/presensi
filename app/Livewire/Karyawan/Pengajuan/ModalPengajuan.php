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
    public $pengajuanId;

    protected $listeners = ['refreshTable' => 'refresh', 'edit-pengajuan' => 'loadData'];

    public function mount()
    {
        $this->shifts = M_JadwalShift::whereIn('nama_shift', 
        [
            'Izin', 
            'Cuti', 
            'Izin Setengah Hari',
        ])->orderBy('nama_shift')->get();
    }

    public function loadData($data)
    {
        // dd($data['id']);
        $this->pengajuanId = $data['id'];
        $this->form->fill($data);
        $this->form->pengajuan = $data['shift_id'];
        $this->form->tanggal = $data['tanggal'];
        $this->form->keterangan = $data['keterangan'];
        $this->file = $data['file'] ? str_replace('storage/', '', $data['file']) : null;
    }
    
    public function saveEdit()
    {
        $dataPengajuan = M_Pengajuan::find($this->pengajuanId);
        // dd($dataPengajuan);
        if (!$dataPengajuan) {
            session()->flash('error', 'Data pengajuan tidak ditemukan!');
            return;
        }
        // $this->form->validate();

        // $this->validate([
        //     'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5048',
        // ]);

        $path = null;
        if ($this->file) {
            $path = $this->file->store('file', 'public');
        }

        $data = [
            'shift_id' => $this->form->pengajuan,
            'tanggal' => $this->form->tanggal,
            'keterangan' => $this->form->keterangan,
            'file' => $path ? str_replace('public/', 'storage/', $path) : null,
        ];
        // dd($data);

        $dataPengajuan->update($data);
        // M_Pengajuan::where('id', Crypt::decrypt($this->form->id))->update($data);

        // Reset input
        $this->form->reset();

        $this->dispatch('swal', params: [
            'title' => 'Data Updated',
            'icon' => 'success',
            'text' => 'Data has been updated successfully'
        ]);

        // Tutup modal
        $this->dispatch('modalEditPengajuan', action: 'hide');
        $this->dispatch('refresh');
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
            'karyawan_id' => M_DataKaryawan::where('user_id', Auth::id())->value('id'),
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
