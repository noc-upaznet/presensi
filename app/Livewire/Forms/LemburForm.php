<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use Livewire\Attributes\Validate;

class LemburForm extends Form
{
    public $id;
    
    #[Validate('required', 'tanggal')]
    public $tanggal = '';

    #[Validate('required', 'Waktu Mulai')]
    public $waktu_mulai = '';

    #[Validate('required', 'Waktu Akhir')]
    public $waktu_akhir = '';

    public $total_jam = 0;

    #[Validate('required', 'Keterangan')]
    public $keterangan = '';
}
