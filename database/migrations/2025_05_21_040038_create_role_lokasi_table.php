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
        Schema::create('role_lokasi', function (Blueprint $table) {
            $table->id();
            $table->integer('karyawan_id');
            $table->boolean('lock')->default(false);
            $table->text('lokasi_presensi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_lokasi');
    }
};
