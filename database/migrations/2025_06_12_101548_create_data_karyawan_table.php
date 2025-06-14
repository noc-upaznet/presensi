<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('data_karyawan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nama_karyawan');
            $table->string('email');
            $table->string('no_hp', 20);
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('jenis_kelamin', 100);
            $table->string('status_perkawinan', 100);
            $table->string('gol_darah', 10)->nullable();
            $table->string('agama', 100);
            $table->string('jenis_identitas', 100);
            $table->string('nik', 16)->nullable();
            $table->string('visa', 20)->nullable();
            $table->text('alamat_ktp');
            $table->text('alamat_domisili');
            $table->string('nip_karyawan', 20);
            $table->string('status_karyawan');
            $table->date('tgl_masuk');
            $table->date('tgl_keluar');
            $table->string('entitas', 100);
            $table->string('divisi', 100);
            $table->string('jabatan', 100);
            $table->string('posisi', 100)->nullable();
            $table->string('sistem_kerja');
            $table->string('spv')->nullable();
            $table->string('gaji_pokok');
            $table->string('tunjangan_jabatan');
            $table->string('bonus');
            $table->string('jenis_penggajian', 100);
            $table->string('nama_bank');
            $table->string('no_rek');
            $table->string('nama_pemilik_rekening');
            $table->string('no_bpjs_tk');
            $table->string('npp_bpjs_tk');
            $table->date('tgl_aktif_bpjstk');
            $table->string('no_bpjs');
            $table->string('anggota_bpjs');
            $table->date('tgl_aktif_bpjs');
            $table->string('penanggung');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_karyawan');
    }
};
