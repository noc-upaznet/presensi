<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payroll', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('karyawan_id');
            $table->string('nip_karyawan')->nullable();
            $table->string('no_slip')->unique();
            $table->string('divisi')->nullable();
            $table->integer('gaji_pokok')->default(0);
            $table->integer('tunjangan_jabatan')->default(0);
            $table->json('tunjangan')->nullable(); // simpan array tunjangan
            $table->json('potongan')->nullable(); // simpan array potongan
            $table->integer('bpjs')->default(0);
            $table->integer('bpjs_jht')->default(0);
            $table->json('rekap')->nullable(); // izin, cuti, dsb
            $table->integer('total_gaji')->default(0);
            $table->string('periode'); // bulan-tahun
            $table->timestamps();

            $table->foreign('karyawan_id')->references('id')->on('data_karyawan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll');
    }
};
