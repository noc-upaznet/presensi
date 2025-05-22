<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('presensi_karyawan', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('divisi');
            $table->enum('status', ['Tepat Waktu', 'Terlambat', 'Izin', 'Cuti', 'Dispensasi', 'Lupa Absen']);
            $table->time('clock_in')->nullable();
            $table->time('clock_out')->nullable();
            $table->string('additional')->nullable();
            $table->date('tanggal');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presensi_karyawan');
    }
};
