<?php

use App\Models\User;
use App\Livewire\Divisi;
use App\Livewire\ClockIn;
use App\Livewire\Entitas;
use App\Livewire\Payroll;
use App\Livewire\Dashboard;
use App\Livewire\LoginPage;
use App\Livewire\RoleUsers;
use Illuminate\Support\Str;
use App\Livewire\ListLokasi;
use App\Livewire\RoleLokasi;
use Illuminate\Http\Request;
use App\Livewire\ClockInSelfie;
use App\Livewire\JenisPotongan;
use App\Livewire\CreateSlipGaji;
use App\Livewire\JenisTunjangan;
use App\Livewire\RiwayatPresensi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use App\Livewire\Karyawan\JadwalShift;
use App\Livewire\Karyawan\DataKaryawan;
use Illuminate\Support\Facades\Session;
use App\Livewire\Karyawan\PembagianShift;
use App\Livewire\GantiPassword;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\SSOLoginController;
use App\Livewire\Karyawan\DetailDataKaryawan;
use App\Livewire\Karyawan\TambahDataKaryawan;
use App\Livewire\Karyawan\Pengajuan\Pengajuan;
use App\Livewire\Karyawan\TambahPembagianShift;
use App\Livewire\Karyawan\Shifts\TemplateMingguan;
use App\Livewire\Karyawan\Pengajuan\PengajuanLembur;


// Route::redirect('/', '/login');
// Route::group(['middleware' => 'auth'], function () {
//     Route::get('/clock-in', ClockIn::class)->name('clock-in');
//     Route::get('/clock-in-selfie', ClockInSelfie::class)->name('clock-in-selfie');
//     Route::get('/dashboard', Dashboard::class)->name('dashboard');
//     Route::view('/profile', 'profile')->name('profile');
//     Route::get('/data-karyawan', DataKaryawan::class)->name('data-karyawan');
//     Route::get('/tambah-data-karyawan', TambahDataKaryawan::class)->name('tambah-data-karyawan');
//     Route::get('/detail-data-karyawan/{id}', DetailDataKaryawan::class)->name('karyawan.detail-data-karyawan');
//     Route::get('/pembagian-shift', PembagianShift::class)->name('pembagian-shift');
//     Route::get('/pembagian-shift/tambah-pembagian-shift', TambahPembagianShift::class)->name('tambah-pembagian-shift');
//     Route::get('/jadwal-shift', JadwalShift::class)->name('jadwal-shift');
//     Route::get('/template-mingguan', TemplateMingguan::class)->name('template-mingguan');
//     Route::get('/pengajuan', Pengajuan::class)->name('pengajuan');
//     Route::get('/pengajuan-lembur', PengajuanLembur::class)->name('pengajuan-lembur');
//     Route::get('/list-lokasi', ListLokasi::class)->name('list-lokasi');
//     Route::get('/role-lokasi', RoleLokasi::class)->name('role-lokasi');
//     Route::get('/role-users', RoleUsers::class)->name('role-users');
//     Route::get('/riwayat-presensi', RiwayatPresensi::class)->name('riwayat-presensi');
//     Route::get('/divisi', Divisi::class)->name('divisi');
//     Route::get('/entitas', Entitas::class)->name('entitas');
//     Route::get('/payroll', Payroll::class)->name('payroll');
//     Route::get('/create-slip-gaji/{id?}', CreateSlipGaji::class)->name('create-slip-gaji');
//     Route::get('/slip-gaji/html/{id}', [PayrollController::class, 'html'])->name('slip.html');
//     Route::get('/slip-gaji/download/{id}', [PayrollController::class, 'download'])->name('slip-gaji.download');
//     Route::get('/jenis-tunjangan', JenisTunjangan::class)->name('jenis-tunjangan');
//     Route::get('/jenis-potongan', JenisPotongan::class)->name('jenis-potongan');
//     // Route::get('/notification-bell', NotificationBell::class)->name('notification-bell');
//     Route::get('/logout', function () {
//         Auth::logout();
//         Session::invalidate();
//         Session::regenerateToken();
    
//         return redirect('/login');
//     })->name('logout');

//     //store a push subscriber.
//     // Route::post('/push','PushController@store');
//     // Route::post('/subscriptions', [PushController::class, 'store']);
//     // dd(app()->getProvider(WebPushServiceProvider::class));
// });



Route::get('/login', function () {
    $role = request()->query('role', 'user');
    if ($role === 'user') {
        $redirectTo = url('/clock-in');
    } else {
        $redirectTo = url('/dashboard');
    }
    session(['redirect_after_login' => $redirectTo]);

    return redirect('http://127.0.0.1:8000/login?redirect_to=' . urlencode(route('sso.callback')));
})->name('login');

Route::get('/auth/callback', function (Request $request) {
    $token = $request->query('token');

    if (!$token) {
        return redirect('http://127.0.0.1:8000/login');
    }

    $response = Http::withToken($token)->get('http://127.0.0.1:8000/api/user');

    if ($response->ok()) {
        $userData = $response->json();

        $user = User::updateOrCreate(
            ['email' => $userData['email']],
            [
                'name' => $userData['name'],
                'role' => $userData['role'] ?? 'user',
                'password' => Hash::make(Str::random(32)),
            ]
        );

        Auth::login($user); // Login dengan session

        if (Auth::check()) {
            $lastUrl = session('redirect_after_login', '/');
            session()->forget('redirect_after_login');

            return redirect($lastUrl);
        }
    }

    return redirect('http://127.0.0.1:8000/login')->withErrors(['Autentikasi gagal']);
})->name('sso.callback');

// Route setelah login
Route::middleware('web')->group(function () {
    Route::get('/', function () {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        if ($user->role === 'admin') {
            return redirect()->route('dashboard');
        } else {
            return redirect()->route('clock-in');
        }
    })->name('home');

    Route::get('/clock-in', ClockIn::class)->name('clock-in');
    Route::get('/clock-in-selfie', ClockInSelfie::class)->name('clock-in-selfie');
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::view('/profile', 'profile')->name('profile');
    Route::get('/data-karyawan', DataKaryawan::class)->name('data-karyawan');
    Route::get('/tambah-data-karyawan', TambahDataKaryawan::class)->name('tambah-data-karyawan');
    Route::get('/detail-data-karyawan/{id}', DetailDataKaryawan::class)->name('karyawan.detail-data-karyawan');
    Route::get('/pembagian-shift', PembagianShift::class)->name('pembagian-shift');
    Route::get('/pembagian-shift/tambah-pembagian-shift', TambahPembagianShift::class)->name('tambah-pembagian-shift');
    Route::get('/jadwal-shift', JadwalShift::class)->name('jadwal-shift');
    Route::get('/template-mingguan', TemplateMingguan::class)->name('template-mingguan');
    Route::get('/pengajuan', Pengajuan::class)->name('pengajuan');
    Route::get('/pengajuan-lembur', PengajuanLembur::class)->name('pengajuan-lembur');
    Route::get('/list-lokasi', ListLokasi::class)->name('list-lokasi');
    Route::get('/role-lokasi', RoleLokasi::class)->name('role-lokasi');
    Route::get('/role-users', RoleUsers::class)->name('role-users');
    Route::get('/riwayat-presensi', RiwayatPresensi::class)->name('riwayat-presensi');
    Route::get('/divisi', Divisi::class)->name('divisi');
    Route::get('/entitas', Entitas::class)->name('entitas');
    Route::get('/payroll', Payroll::class)->name('payroll');
    Route::get('/create-slip-gaji/{id?}', CreateSlipGaji::class)->name('create-slip-gaji');
    Route::get('/slip-gaji/html/{id}', [PayrollController::class, 'html'])->name('slip.html');
    Route::get('/slip-gaji/download/{id}', [PayrollController::class, 'download'])->name('slip-gaji.download');
    Route::get('/jenis-tunjangan', JenisTunjangan::class)->name('jenis-tunjangan');
    Route::get('/jenis-potongan', JenisPotongan::class)->name('jenis-potongan');
    Route::get('/ganti-password', GantiPassword::class)->name('ganti-password');
    Route::get('/logout', function () {
        Auth::logout();
        Session::invalidate();
        Session::regenerateToken();
    
        return redirect('/login');
    })->name('logout');
});

// require __DIR__.'/auth.php';