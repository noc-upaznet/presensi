<?php

use App\Livewire\Dashboard;
use App\Livewire\GantiPassword;
use Illuminate\Support\Facades\Route;
use App\Livewire\Karyawan\JadwalShift;
use App\Livewire\Karyawan\DataKaryawan;
use App\Livewire\Karyawan\TambahJadwalShift;
use App\Livewire\Karyawan\DetailDataKaryawan;
use App\Livewire\Karyawan\TambahDataKaryawan;
use App\Livewire\PengajuanCutiIzin;
use App\Livewire\PengajuanLembur;
use App\Livewire\ProfilePic;
use App\Livewire\RiwayatPresensi;
use Symfony\Component\HttpKernel\Profiler\Profile;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', Dashboard::class)->name('dashboard');

Route::get('/data-karyawan', DataKaryawan::class)->name('data-karyawan');

Route::get('/tambah-data-karyawan', TambahDataKaryawan::class)->name('tambah-data-karyawan');
Route::get('/detail-data-karyawan', DetailDataKaryawan::class)->name('detail-data-karyawan');
Route::get('/jadwal-shift', JadwalShift::class)->name('jadwal-shift');
Route::get('/jadwal-shift/tambah-jadwal-shift', TambahJadwalShift::class)->name('tambah-jadwal-shift');

Route::get('/riwayat-presensi', RiwayatPresensi::class)->name('riwayat-presensi');

Route::get('/pengajuan-izin-cuti', PengajuanCutiIzin::class)->name('pengajuan-izin-cuti');

Route::get('/ganti-password', GantiPassword::class)->name('ganti-password');

Route::get('/pengajuan-lembur',PengajuanLembur::class)->name('pengajuan-lembur');

Route::get('/profile-pic', ProfilePic::class)->name('profile-pic'); 