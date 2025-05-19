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
        Schema::create('lembur', function (Blueprint $table) {
            $table->id();
            $table->integer('karyawan_id');
            $table->date('tanggal');
            $table->time('waktu_mulai');
            $table->time('waktu_akhir');
            $table->string('keterangan');
            $table->string('file_bukti')->nullable();
            $table->integer('status')->default(0)->comment('0 = menunggu, 1 = diterima, 2 = ditolak');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lembur');
    }
};
