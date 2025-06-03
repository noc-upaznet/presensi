<?php

use App\Livewire\ClockIn;
use App\Livewire\Dashboard;
use App\Livewire\ListLokasi;
use App\Livewire\RoleLokasi;
use App\Livewire\ClockInSelfie;
use App\Livewire\RiwayatPresensi;
use App\Livewire\NotificationBell;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Livewire\Karyawan\JadwalShift;
use App\Livewire\Karyawan\DataKaryawan;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\PushController;
use App\Livewire\Karyawan\PembagianShift;
use App\Livewire\Karyawan\DetailDataKaryawan;
use App\Livewire\Karyawan\TambahDataKaryawan;
use App\Livewire\Karyawan\Pengajuan\Pengajuan;
use App\Livewire\Karyawan\TambahPembagianShift;
use App\Livewire\Karyawan\Shifts\TemplateMingguan;
use App\Livewire\Karyawan\Pengajuan\PengajuanLembur;
use NotificationChannels\WebPush\WebPushServiceProvider;

Route::view('/', 'welcome');
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
    Route::get('/riwayat-presensi', RiwayatPresensi::class)->name('riwayat-presensi');
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

require __DIR__.'/auth.php';