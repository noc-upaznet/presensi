<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use Livewire\Attributes\Validate;

class TambahPembagianShiftForm extends Form
{
    public $id;

    #[Validate('required', 'Nama Shift')]
    public $nama_shift = '';

    #[Validate('required', 'Deskripsi')]
    public $deskripsi = '';
    
    // #[Validate('required', 'Jenis Shift')]
    // public $jenis_shift = '';

    // #[Validate('required', 'Jam Masuk')]
    // public $jam_masuk = '';

    // #[Validate('required', 'Jam Pulang')]
    // public $jam_pulang = '';
}
