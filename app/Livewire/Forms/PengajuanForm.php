<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use Livewire\Attributes\Validate;

class PengajuanForm extends Form
{
    public $id;

    #[Validate('required', 'Nama Karyawan')]
    public $nama_karyawan = '';

    #[Validate('required', 'Pengajuan')]
    public $pengajuan = '';
    
    #[Validate('required', 'tanggal')]
    public $tanggal = '';

    #[Validate('required', 'Keterangan')]
    public $keterangan = '';
}
