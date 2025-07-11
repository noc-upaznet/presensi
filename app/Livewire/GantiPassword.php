<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class GantiPassword extends Component
{
    public $current_password;
    public $new_password;
    public $confirm_password;

    public function updatePassword()
    {
        $user = Auth::user();

        // Debug log untuk melihat password input dan hash dari database
        logger('Input password: ' . $this->current_password);
        logger('Hash in DB: ' . $user->password);
        logger('Hash check result: ' . (Hash::check($this->current_password, $user->password) ? '✅ Cocok' : '❌ Tidak cocok'));

        // Validasi password sekarang
        if (!Hash::check($this->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'Password yang kamu masukkan salah.',
            ]);
        }

        // Validasi password baru dan konfirmasi
        $this->validate([
            'new_password' => ['required', Password::defaults(), 'same:confirm_password'],
            'confirm_password' => ['required'],
        ]);

        // Update password
        $user->update([
            'password' => Hash::make($this->new_password),
        ]);

        // Reset input field
        $this->reset(['current_password', 'new_password', 'confirm_password']);

        session()->flash('success', 'Password berhasil diperbarui.');
    }

    public function render()
    {
        return view('livewire.ganti-password');
    }
}
