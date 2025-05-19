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
        Schema::create('pencairan_gaji', function (Blueprint $table) {
            $table->id();
            $table->string('nama_karyawan');
            $table->string('pendapatan');
            $table->string('tunjangan');
            $table->string('bonus');
            $table->string('potongan');
            $table->string('total_gaji');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
