<?php

use App\Livewire\Dashboard;
use Illuminate\Support\Facades\Route;
use App\Livewire\Karyawan\JadwalShift;
use App\Livewire\Karyawan\DataKaryawan;
use App\Livewire\Karyawan\PembagianShift;
use App\Livewire\Karyawan\DetailDataKaryawan;
use App\Livewire\Karyawan\Pengajuan\Pengajuan;
use App\Livewire\Karyawan\Shifts\TemplateMingguan;
use App\Livewire\Karyawan\TambahDataKaryawan;
use App\Livewire\Karyawan\TambahPembagianShift;
use App\Livewire\ListLokasi;
use App\Livewire\PencairanGaji;
use App\Livewire\RoleLokasi;
use App\Livewire\Payroll;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', Dashboard::class)->name('dashboard');

Route::get('/data-karyawan', DataKaryawan::class)->name('data-karyawan');

Route::get('/tambah-data-karyawan', TambahDataKaryawan::class)->name('tambah-data-karyawan');
Route::get('/detail-data-karyawan/{id}', DetailDataKaryawan::class)->name('karyawan.detail-data-karyawan');
Route::get('/pembagian-shift', PembagianShift::class)->name('pembagian-shift');
Route::get('/pembagian-shift/tambah-pembagian-shift', TambahPembagianShift::class)->name('tambah-pembagian-shift');
Route::get('/jadwal-shift', JadwalShift::class)->name('jadwal-shift');
Route::get('/template-mingguan', TemplateMingguan::class)->name('template-mingguan');
Route::get('/pengajuan', Pengajuan::class)->name('pengajuan');

Route::get('/list-lokasi', ListLokasi::class)->name('list-lokasi');
Route::get('/role-lokasi', RoleLokasi::class)->name('role-lokasi');
Route::get('/pencairan-gaji', PencairanGaji::class)->name('pencairan-gaji'); 
Route::get('/payroll', Payroll::class)->name('payroll');