<?php

use App\Livewire\Divisi;
use App\Livewire\ClockIn;
use App\Livewire\Entitas;
use App\Livewire\Payroll;
use App\Livewire\Dashboard;
use App\Livewire\RoleUsers;
use App\Livewire\ListLokasi;
use App\Livewire\RoleLokasi;
use App\Livewire\ClockInSelfie;
use App\Livewire\GantiPassword;
use App\Livewire\JenisPotongan;
use App\Livewire\CreateSlipGaji;
use App\Livewire\JenisTunjangan;
use App\Livewire\RiwayatPresensi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Livewire\Karyawan\JadwalShift;
use App\Livewire\Karyawan\DataKaryawan;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\AuthController;
use App\Livewire\Karyawan\PembagianShift;
use App\Http\Controllers\PayrollController;
use App\Livewire\Karyawan\DetailDataKaryawan;
use App\Livewire\Karyawan\TambahDataKaryawan;
use App\Livewire\Karyawan\Pengajuan\Pengajuan;
use App\Livewire\Karyawan\TambahPembagianShift;
use App\Livewire\Karyawan\Shifts\TemplateMingguan;
use App\Livewire\Karyawan\Pengajuan\PengajuanLembur;

// Route::redirect('/', '/login');
Route::get('/', function () {
    return redirect()->route('login');
});
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::group(['middleware' => 'auth'], function () {
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
    // Route::get('/notification-bell', NotificationBell::class)->name('notification-bell');
    Route::get('/logout', function () {
        Auth::logout();
        Session::invalidate();
        Session::regenerateToken();
    
        return redirect('/login');
    })->name('logout');

    //store a push subscriber.
    // Route::post('/push','PushController@store');
    // Route::post('/subscriptions', [PushController::class, 'store']);
    // dd(app()->getProvider(WebPushServiceProvider::class));
});

// Route::get('admin', function () {
//     return '<h1>Admin Page</h1><p>Only accessible by users with the admin role.</p>';
// })->middleware(['auth', 'role:admin']);

// require __DIR__.'/auth.php';