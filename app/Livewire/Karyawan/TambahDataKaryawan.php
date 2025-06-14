<?php

namespace App\Livewire\Karyawan;

use App\Models\User;
use Livewire\Component;
use App\Models\M_Divisi;
use App\Models\M_Entitas;
use App\Models\M_Jabatan;
use App\Models\M_DataKaryawan;
use App\Livewire\Forms\TambahDataKaryawanForm;

class TambahDataKaryawan extends Component
{
    public TambahDataKaryawanForm $form;

    public $step = 1;

    public $identitas = [
        'jenis_identitas' => '',
        'nomorKTP' => '',
        'nomorVISA' => '',
    ];
    public $jenis_identitas = '';
    public $entitas;
    public $divisi;
    public $jabatan;
    public $password;

    public function mount()
    {
        $this->entitas = M_Entitas::all();
        $this->divisi = M_Divisi::all();
        $this->jabatan = M_Jabatan::all();
    }

    public function updatedFormGunakanAlamatKTP($value)
    {
        if ($value) {
            $this->form->alamatDomisili = $this->form->alamatKTP;
        } else {
            $this->form->alamatDomisili = '';
        }
    }

    // public function updatedFormAlamatKTP($value)
    // {
    //     if ($this->form->gunakanAlamatKTP) {
    //         $this->form->alamatDomisili = $value;
    //     }
    // }

    public function nextStep() {
        $this->step++;
    }

    public function prevStep() {
        $this->step--;
    }

    public function store() {
        $this->validate([
            'password' => 'required',
        ]);
        $this->form->validate();

        $user = User::create([
            'name' => $this->form->nama_karyawan,
            'email' => $this->form->email,
            'password' => bcrypt($this->password),
        ]);
        // dd($user);
        
        $data = [
            'user_id' => $user->id,
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
            'sistem_kerja' => $this->form->sistem_kerja,
            'gaji_pokok' => $this->form->gaji_pokok,
            'tunjangan_jabatan' => $this->form->tunjangan_jabatan,
            'bonus' => $this->form->bonus,
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

        // dd($data, $data2);
            
        M_DataKaryawan::create($data);
        
        $this->form->reset();

        $this->dispatch('swal', params: [
            'title' => 'Data Saved',
            'icon' => 'success',
            'text' => 'Data has been saved successfully'
        ]);

        redirect()->route('data-karyawan');
    }
    public function render()
    {
        return view('livewire.karyawan.tambah-data-karyawan');
    }
}
