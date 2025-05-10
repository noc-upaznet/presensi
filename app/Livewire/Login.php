<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    public $email = '';
    public $password = '';

    public function login()
    {
        dd($this->email, $this->password);
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            session()->regenerate();
            return redirect()->intended('/dashboard');
        } else {
            $this->addError('email', 'Email atau password salah.');
        }
    }
    
    public function render()
    {
        return view('livewire.login');
    }
}
