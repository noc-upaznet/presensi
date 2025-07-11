<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component
{
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('password-updated');
    }
}; ?>

<section class="bg-white p-6 sm:p-8 rounded-xl shadow-md border border-gray-200">
    <header class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
            <i class="fas fa-lock text-indigo-600"></i> Ganti Password
        </h2>
        <p class="text-sm text-gray-500 mt-1">
            Gunakan kombinasi password yang kuat dan unik untuk menjaga keamanan akun Anda.
        </p>
    </header>

    <form wire:submit="updatePassword" class="space-y-5">
        <!-- Password saat ini -->
        <div>
            <x-input-label for="update_password_current_password" :value="'Password Saat Ini'" />
            <x-text-input
                wire:model.defer="current_password"
                id="update_password_current_password"
                name="current_password"
                type="password"
                class="mt-1 block w-full"
                placeholder="Masukkan password sekarang"
                autocomplete="current-password"
            />
            <x-input-error :messages="$errors->get('current_password')" class="mt-2" />
        </div>

        <!-- Password baru -->
        <div>
            <x-input-label for="update_password_password" :value="'Password Baru'" />
            <x-text-input
                wire:model.defer="password"
                id="update_password_password"
                name="password"
                type="password"
                class="mt-1 block w-full"
                placeholder="Masukkan password baru"
                autocomplete="new-password"
            />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Konfirmasi password -->
        <div>
            <x-input-label for="update_password_password_confirmation" :value="'Konfirmasi Password Baru'" />
            <x-text-input
                wire:model.defer="password_confirmation"
                id="update_password_password_confirmation"
                name="password_confirmation"
                type="password"
                class="mt-1 block w-full"
                placeholder="Ulangi password baru"
                autocomplete="new-password"
            />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Tombol simpan -->
        <div class="flex items-center justify-between pt-4 border-t mt-6">
            <x-primary-button class="px-6 py-2">
                <i class="fas fa-save me-2"></i> Simpan Perubahan
            </x-primary-button>

            <x-action-message class="text-green-600 text-sm font-semibold" on="password-updated">
                <i class="fas fa-check-circle me-1"></i> Password berhasil diperbarui!
            </x-action-message>
        </div>
    </form>
</section>

