<?php

use App\Livewire\Divisi;
use App\Livewire\ClockIn;
use App\Livewire\Entitas;
use App\Livewire\Payroll;
use App\Livewire\DataUser;
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
use App\Livewire\RiwayatPresensiStaff;
use App\Livewire\Karyawan\DataKaryawan;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\AuthController;
use App\Livewire\Karyawan\PembagianShift;
use App\Http\Controllers\PayrollController;
use App\Livewire\EditPayroll;
use App\Livewire\Karyawan\DetailDataKaryawan;
use App\Livewire\Karyawan\Pengajuan\Dispensasi;
use App\Livewire\Karyawan\TambahDataKaryawan;
use App\Livewire\Karyawan\Pengajuan\Pengajuan;
use App\Livewire\Karyawan\TambahPembagianShift;
use App\Livewire\Karyawan\Shifts\TemplateMingguan;
use App\Livewire\Karyawan\Pengajuan\PengajuanLembur;
use App\Livewire\Karyawan\Pengajuan\Sharing;
use App\Livewire\ManageUser;
use App\Livewire\SlipGaji;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

// Route::redirect('/', '/login');
// Halaman lupa password
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

// Proses reset password langsung
Route::post('/forgot-password', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return back()->withErrors(['email' => 'Email tidak ditemukan']);
    }

    $user->password = Hash::make($request->password);
    $user->save();

    return redirect()->route('login')->with('status', 'Password berhasil direset, silahkan login.');
})->name('password.update');


Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/ganti-password', GantiPassword::class)->middleware('auth')->name('ganti-password');
Route::group(['middleware' => ['auth', 'password.expired', 'session.expired']], function () {
    Route::get('/', Dashboard::class)->name('dashboard')->middleware('check.dashboard-view');
    Route::get('/clock-in', ClockIn::class)->name('clock-in');
    Route::get('/clock-in-selfie', ClockInSelfie::class)->name('clock-in-selfie');
    Route::view('/profile', 'profile')->name('profile');
    Route::get('/data-karyawan', DataKaryawan::class)->name('data-karyawan')->middleware('check.data-karyawan');
    Route::get('/tambah-data-karyawan', TambahDataKaryawan::class)->name('tambah-data-karyawan');
    Route::get('/detail-data-karyawan/{id}', DetailDataKaryawan::class)->name('karyawan.detail-data-karyawan');
    Route::get('/pembagian-shift', PembagianShift::class)->name('pembagian-shift');
    Route::get('/pembagian-shift/tambah-pembagian-shift', TambahPembagianShift::class)->name('tambah-pembagian-shift');
    Route::get('/jadwal-shift', JadwalShift::class)->name('jadwal-shift')->middleware('check.jadwal-shift');
    Route::get('/template-mingguan', TemplateMingguan::class)->name('template-mingguan');
    Route::get('/pengajuan', Pengajuan::class)->name('pengajuan');
    Route::get('/pengajuan-lembur', PengajuanLembur::class)->name('pengajuan-lembur');
    Route::get('/list-lokasi', ListLokasi::class)->name('list-lokasi');
    Route::get('/role-lokasi', RoleLokasi::class)->name('role-lokasi');
    Route::get('/data-masters', RoleUsers::class)->name('data-masters');
    Route::get('/data-user', DataUser::class)->name('data-user');
    Route::get('/riwayat-presensi', RiwayatPresensi::class)->name('riwayat-presensi');
    Route::get('/divisi', Divisi::class)->name('divisi');
    Route::get('/entitas', Entitas::class)->name('entitas');
    Route::get('/payroll', Payroll::class)->name('payroll')->middleware('check.payroll');
    Route::get('/edit-payroll/{id}', EditPayroll::class)->name('edit-payroll');
    Route::get('/create-slip-gaji/{id?}/{month?}/{year?}', CreateSlipGaji::class)->name('create-slip-gaji');
    Route::get('/create-slip-gaji-tambah/{month?}/{year?}', CreateSlipGaji::class)
        ->name('create-slip-gaji-tambah');
    Route::get('/slip-gaji/html/{id}', [PayrollController::class, 'html'])->name('slip.html');
    Route::get('/slip-gaji/download/{id}', [PayrollController::class, 'download'])->name('slip-gaji.download');
    Route::get('/jenis-tunjangan', JenisTunjangan::class)->name('jenis-tunjangan');
    Route::get('/jenis-potongan', JenisPotongan::class)->name('jenis-potongan');
    Route::get('/riwayat-presensi-staff', RiwayatPresensiStaff::class)->name('riwayat-presensi-staff');
    Route::get('/slip-gaji', SlipGaji::class)->name('slip-gaji');
    Route::get('/manage-user', ManageUser::class)->name('manage-user');
    Route::get('/dispensasi', Dispensasi::class)->name('dispensasi');
    Route::get('/sharing', Sharing::class)->name('sharing');
});
