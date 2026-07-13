<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use Livewire\Attributes\Validate;

class DispensasiForm extends Form
{
    public $id;

    // #[Validate('required', 'type')]
    public $type = '';

    #[Validate('required', 'date')]
    public $date = '';

    #[Validate('required', 'description')]
    public $description = '';
}
