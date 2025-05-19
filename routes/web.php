<?php

use App\Livewire\MapFix;
use App\Livewire\Dashboard;
use App\Livewire\NotificationBell;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Livewire\Karyawan\JadwalShift;
use App\Livewire\Karyawan\DataKaryawan;
use Illuminate\Support\Facades\Session;
use App\Livewire\Karyawan\PembagianShift;
use App\Livewire\Karyawan\DetailDataKaryawan;
use App\Livewire\Karyawan\TambahDataKaryawan;
use App\Livewire\Karyawan\Pengajuan\Pengajuan;
use App\Livewire\Karyawan\TambahPembagianShift;
use App\Livewire\Karyawan\Shifts\TemplateMingguan;
use App\Livewire\Karyawan\Pengajuan\PengajuanLembur;

Route::view('/', 'welcome');
Route::group(['middleware' => 'auth'], function () {
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
    Route::get('/map-fix', MapFix::class)->name('map-fix');
    Route::post('/logout', function () {
        Auth::logout();
        Session::invalidate();
        Session::regenerateToken();
    
        return redirect('/login');
    })->name('logout');
});