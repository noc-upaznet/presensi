<?php

namespace App\Livewire\Karyawan;

use Livewire\Component;
use App\Models\M_Entitas;
use App\Models\M_Divisi;
use App\Models\M_Jabatan;
use Illuminate\Http\Request;
use Livewire\WithFileUploads;
use App\Models\M_DataKaryawan;
use App\Imports\KaryawanImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\Livewire\Forms\TambahDataKaryawanForm;
use App\Models\User;

class ModalKaryawan extends Component
{
    public TambahDataKaryawanForm $form;
    use WithFileUploads;

    public $file;

    public $ticketId;
    public $entitas;
    public $divisi;
    public $jabatan;
    protected $listeners = ['edit-ticket' => 'loadTicketData'];

    public function mount()
    {
        $this->entitas = M_Entitas::all();
        $this->divisi = M_Divisi::all();
        $this->jabatan = M_Jabatan::all();
        // dd($this->jabatan);
    }

    public function updatedFormTotalUpah($value)
    {
        // dd($value);
        $value = (int) str_replace('.', '', $value);
        $this->form->gaji_pokok = $value * 0.75;
        $this->form->tunjangan_jabatan = $value * 0.25;
    }
    public function loadTicketData($data)
    {
        // dd($data);
        $this->ticketId = $data['id'];
        $this->form->fill($data);
        // dd($data);
        $this->form->alamatKTP = $data['alamat_ktp'] ?? '';
        $this->form->alamatDomisili = $data['alamat_domisili'] ?? '';
        $this->form->nomorKTP = $data['nik'] ?? '';
        $this->form->nomorVISA = $data['visa'] ?? null;

    }

    public function saveEdit() {
        $ticket = M_DataKaryawan::find($this->ticketId);
        // dd($ticket);
        if (!$ticket) {
            session()->flash('error', 'Data karyawan tidak ditemukan!');
            return;
        }
    
        $data = [
            'nama_karyawan' => $this->form->nama_karyawan,
            'email' => $this->form->email,
            'no_hp' => $this->form->no_hp,
            'tempat_lahir' => $this->form->tempat_lahir,
            'tanggal_lahir' => $this->form->tanggal_lahir,
            'jenis_kelamin' => $this->form->jenis_kelamin,
            'status_perkawinan' => $this->form->status_perkawinan,
            'gol_darah' => $this->form->gol_darah,
            'agama' => $this->form->agama,
            'jenis_identitas' => $this->form->jenis_identitas,
            'nik' => $this->form->nomorKTP,
            'visa' => $this->form->nomorVISA,
            'alamat_ktp' => $this->form->alamatKTP,
            'alamat_domisili' => $this->form->alamatDomisili,
            'nip_karyawan' => $this->form->nip_karyawan,
            'status_karyawan' => $this->form->status_karyawan,
            'tgl_masuk' => $this->form->tgl_masuk,
            'tgl_keluar' => $this->form->tgl_keluar,
            'entitas' => $this->form->entitas,
            'divisi' => $this->form->divisi,
            'jabatan' => $this->form->jabatan,
            'level' => $this->form->level,
            'sistem_kerja' => $this->form->sistem_kerja,
            // 'spv' => $this->form->spv,
            'total_upah' => $this->form->total_upah,
            'gaji_pokok' => $this->form->gaji_pokok,
            'tunjangan_jabatan' => $this->form->tunjangan_jabatan,
            'bonus' => $this->form->bonus,
            'inov_reward' => $this->form->inov_reward,
            'jenis_penggajian' => $this->form->jenis_penggajian,
            'nama_bank' => $this->form->nama_bank,
            'no_rek' => $this->form->no_rek,
            'nama_pemilik_rekening' => $this->form->nama_pemilik_rekening,
            'no_bpjs_tk' => $this->form->no_bpjs_tk,
            'npp_bpjs_tk' => $this->form->npp_bpjs_tk,
            'tgl_aktif_bpjstk' => $this->form->tgl_aktif_bpjstk,
            'no_bpjs' => $this->form->no_bpjs,
            'anggota_bpjs' => $this->form->anggota_bpjs,
            'tgl_aktif_bpjs' => $this->form->tgl_aktif_bpjs,
            'penanggung' => $this->form->penanggung,
        ];
        // dd($data);
        User::where('id', $ticket->user_id)->update([
            'name' => $this->form->nama_karyawan,
            'email' => $this->form->email,
            'current_role' => strtolower($this->form->level) === 'staff' ? 'user' : strtolower($this->form->level),
        ]);
        $ticket->update($data);

        $this->form->reset();
        $this->dispatch('swal', params: [
            'title' => 'Data Updated',
            'icon' => 'success',
            'text' => 'Data has been updated successfully'
        ]);

        $this->dispatch('modal-edit-data-karyawan', action: 'hide');
        $this->dispatch('refresh');
    }

    public function saveImport()
    {
        $this->validate([
            'file' => 'required|mimes:xlsx,csv,xls',
        ]);
    
        // Ambil file dari temporary path
        $filePath = $this->file->getRealPath();
    
        // Import langsung dari file temporary
        Excel::import(new KaryawanImport, $filePath);
        // Kirim notifikasi sukses
        $this->dispatch('swal', params: [
            'title' => 'Berhasil!',
            'text' => 'Data berhasil di-import.',
            'icon' => 'success'
        ]);

        $this->dispatch('modal-import', action: 'hide');
        $this->dispatch('refresh');
    }

    public function render()
    {
        return view('livewire.karyawan.modal-karyawan');
    }
}
