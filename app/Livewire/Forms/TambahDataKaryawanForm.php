<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use Livewire\Attributes\Validate;

class TambahDataKaryawanForm extends Form
{
    public $id;

    #[Validate('required', 'Nama Karyawan')]
    public $nama_karyawan = '';

    #[Validate('required', 'Email')]
    public $email = '';
    
    #[Validate('required', 'No HP')]
    public $no_hp = '';

    #[Validate('required', 'Penanggung')]   
    public $penanggung = '';

    #[Validate('required', 'Tempat Lahir')]
    public $tempat_lahir = '';

    #[Validate('required', 'Tanggal Lahir')]
    public $tanggal_lahir = '';

    #[Validate('required', 'Jenis Kelamin')]
    public $jenis_kelamin = '';

    #[Validate('required', 'Jenis Identitas')]
    public $jenis_identitas = '';

    #[Validate('required', 'Alamat KTP')]
    public string $alamatKTP = '';

    #[Validate('required', 'Alamat Domisili')]
    public string $alamatDomisili = '';

    #[Validate('required', 'NPK/NIP Karyawan')]
    public $nip_karyawan = '';

    #[Validate('required', 'Status Karyawan')]
    public $status_karyawan = '';

    #[Validate('required', 'Tanggal Masuk')]
    public $tgl_masuk = '';

    #[Validate('required', 'Tanggal Keluar')]
    public $tgl_keluar = '';

    #[Validate('required', 'Entitas')]
    public $entitas = '';

    #[Validate('required', 'Divisi')]
    public $divisi = '';

    #[Validate('required', 'Jabatan')]
    public $jabatan = '';

    #[Validate('required', 'Sistem Kerja')]
    public $sistem_kerja = '';

    #[Validate('required', 'Gaji Pokok')]
    public $gaji_pokok = '';

    #[Validate('required', 'Tunjangan Jabatan')]
    public $tunjangan_jabatan = '';

    #[Validate('required', 'Jenis Penggajian')]
    public $jenis_penggajian = '';

    public $bonus = '';

    #[Validate('required', 'Nama Bank')]
    public $nama_bank = '';

    #[Validate('required', 'No Rek')]
    public $no_rek = '';

    #[Validate('required', 'Nama Pemilik Rekening')]
    public $nama_pemilik_rekening = '';

    #[Validate('required', 'No BPJS TK')]   
    public $no_bpjs_tk = '';

    #[Validate('required', 'NPP BPJS TK')]
    public $npp_bpjs_tk = '';

    #[Validate('required', 'Tanggal Aktif BPJS TK')]
    public $tgl_aktif_bpjstk = '';

    #[Validate('required', 'No BPJS Kesehatan')]    
    public $no_bpjs = '';

    #[Validate('required', 'Anggota BPJS Kesehatan')]
    public $anggota_bpjs = '';

    #[Validate('required', 'tanggal Aktif BPJS Kesehatan')]
    public $tgl_aktif_bpjs = '';

    
    public $status_perkawinan;
    public $gol_darah;
    public $agama;
    public $nomorKTP;
    public $nomorVISA;

    public bool $gunakanAlamatKTP = false;
}
