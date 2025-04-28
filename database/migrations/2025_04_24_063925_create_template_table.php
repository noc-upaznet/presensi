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
        Schema::create('template_week', function (Blueprint $table) {
            $table->id();
            $table->string('nama_template');
            $table->integer('minggu');
            $table->integer('senin');
            $table->integer('selasa');
            $table->integer('rabu');
            $table->integer('kamis');
            $table->integer('jumat');
            $table->integer('sabtu');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('template');
    }
};
