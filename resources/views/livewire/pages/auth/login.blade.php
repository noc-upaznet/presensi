<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="login-box w-100" style="max-width: 400px;">
    <div class="card card-outline card-primary shadow">
        <div class="card-header text-center">
            <a href="#" class="h1"><b>Sistem</b> Presensi</a>
        </div>
        <div class="card-body">
            <p class="login-box-msg mb-2" align="center">Silahkan Login</p>

            <!-- Session Status -->
            @if (session('status'))
                <div class="alert alert-success mb-3">
                    {{ session('status') }}
                </div>
            @endif

            <form wire:submit.prevent="login">
                {{-- Email --}}
                <div class="input-group mb-3">
                    <input wire:model.defer="form.email" type="email" class="form-control" placeholder="Email" required autofocus>
                    <div class="input-group-text">
                        <i class="fas fa-envelope"></i>
                    </div>
                </div>
                @error('form.email') <span class="text-danger">{{ $message }}</span> @enderror

                {{-- Password --}}
                <div class="input-group mb-3">
                    <input wire:model.defer="form.password" type="password" class="form-control" placeholder="Password" required>
                    <div class="input-group-text">
                        <i class="fas fa-lock"></i>
                    </div>
                </div>
                @error('form.password') <span class="text-danger">{{ $message }}</span> @enderror

                {{-- Remember Me --}}
                {{-- <div class="form-check mb-3">
                    <input wire:model="form.remember" type="checkbox" class="form-check-input" id="remember">
                    <label class="form-check-label" for="remember">Ingat Saya</label>
                </div> --}}

                {{-- Tombol Login --}}
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Login</button>
                    {{-- <a href="#" class="btn btn-success">Login Admin</a> --}}
                </div>

                {{-- Forgot Password --}}
                {{-- <div class="mt-3 text-center">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-muted">Lupa Password?</a>
                    @endif
                </div> --}}
            </form>
        </div>
    </div>
</div>