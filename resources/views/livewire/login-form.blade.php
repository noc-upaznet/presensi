
<div class="login-page bg-light" style="min-height: 100vh;">
    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="#" class="h1"><b>Sistem</b> Presensi</a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Silahkan Login</p>

                <form action="{{ route('login') }}" method="POST">
                    @csrf

                    {{-- Email --}}
                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control" placeholder="Email" required autofocus>
                        <div class="input-group-text">
                            <i class="fas fa-envelope"></i>
                        </div>
                    </div>

                    {{-- Password --}}
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                        <div class="input-group-text">
                            <i class="fas fa-lock"></i>
                        </div>
                    </div>

                    {{-- Tombol Login --}}
                    <div class="row">
                        <div class="col-12 mb-2">
                            <button type="submit" class="btn btn-primary btn-block w-100">Login</button>
                        </div>
                        <div class="col-12">
                            <a href="#" class="btn btn-success btn-block w-100">Login Admin</a>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
