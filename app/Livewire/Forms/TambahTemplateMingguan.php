<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use Livewire\Attributes\Validate;

class TambahTemplateMingguan extends Form
{
    public $id;

    #[Validate('required', 'Nama Template')]
    public $nama_template = '';

    #[Validate('required', 'Minggu')]
    public $minggu = '';

    #[Validate('required', 'Senin')]
    public $senin = '';

    #[Validate('required', 'Selasa')]
    public $selasa = '';

    #[Validate('required', 'Rabu')]
    public $rabu = '';

    #[Validate('required', 'Kamis')]
    public $kamis = '';

    #[Validate('required', 'Jumat')]
    public $jumat = '';

    #[Validate('required', 'Sabtu')]
    public $sabtu = '';
}
