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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->string('no_gaji')->unique();
            $table->string('nama');
            $table->string('jabatan');
            $table->integer('bulan');
            $table->integer('tahun');
            $table->bigInteger('kasbon')->default(0);
            $table->bigInteger('total');
            $table->integer('status')->default(0)->comment('0: Pending, 1: On Progress, 2: Success');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};