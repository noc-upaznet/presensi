<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class UserForm extends Form
{

    public $id;

    #[Validate('required', 'Nama')]
    public $name = '';

    #[Validate('required|email', 'Email')]
    public $email = '';

    #[Validate('required|min:8|confirmed', 'Password')]
    public $password = '';

    #[Validate('required|min:8', 'Password Confirmation')]
    public $password_confirmation = '';

    #[Validate('required', 'Roles')]
    public $user_roles;

    #[Validate('required', 'Permissions')]
    public $user_permissions;

    #[Validate('required', 'Branches')]
    public $user_branches;
}
