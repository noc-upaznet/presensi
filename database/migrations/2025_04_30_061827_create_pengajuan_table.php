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
        Schema::create('pengajuan', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->integer('karyawan_id');
            $table->integer('shift_id');
            $table->string('keterangan');
            $table->integer('status')->default(0)->comment('0 = menunggu, 1 = diterima, 2 = ditolak');
            $table->integer('jadwal_sebelumnya')->nullable();  // Perbaiki di sini
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan');
    }
};
