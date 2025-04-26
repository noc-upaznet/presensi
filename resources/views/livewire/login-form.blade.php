<div class="login-page bg-light d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <div class="login-box w-100" style="max-width: 400px;">
        <div class="card card-outline card-primary shadow">
            <div class="card-header text-center">
                <a href="#" class="h1"><b>Sistem</b>Presensi</span></a>
            </div>
            <div class="card-body">
                <p class="login-box-msg mb-2">Silahkan Login</p>

                <form wire:submit.prevent="login">

                    {{-- Email --}}
                    <div class="input-group mb-3">
                        <input wire:model.defer="email" type="email" class="form-control" placeholder="Email" required autofocus>
                        <div class="input-group-text">
                            <i class="fas fa-envelope"></i>
                        </div>
                    </div>
                    @error('email') <span class="text-danger">{{ $message }}</span> @enderror

                    {{-- Password --}}
                    <div class="input-group mb-4">
                        <input wire:model.defer="password" type="password" class="form-control" placeholder="Password" required>
                        <div class="input-group-text">
                            <i class="fas fa-lock"></i>
                        </div>
                    </div>
                    @error('password') <span class="text-danger">{{ $message }}</span> @enderror

                    {{-- Tombol Login --}}
                    <div class="row">
                        <div class="col-12 mb-2">
                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </div>
                        <div class="col-12">
                            <a href="#" class="btn btn-success w-100">Login Admin</a>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
