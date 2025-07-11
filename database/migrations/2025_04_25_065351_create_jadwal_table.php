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
        Schema::create('jadwal', function (Blueprint $table) {
            $table->id();
            $table->string('bulan_tahun');
            $table->integer('user_id');
            $table->integer('d1')->nullable();
            $table->integer('d2')->nullable();
            $table->integer('d3')->nullable();
            $table->integer('d4')->nullable();
            $table->integer('d5')->nullable();
            $table->integer('d6')->nullable();
            $table->integer('d7')->nullable();
            $table->integer('d8')->nullable();
            $table->integer('d9')->nullable();
            $table->integer('d10')->nullable();
            $table->integer('d11')->nullable();
            $table->integer('d12')->nullable();
            $table->integer('d13')->nullable();
            $table->integer('d14')->nullable();
            $table->integer('d15')->nullable();
            $table->integer('d16')->nullable();
            $table->integer('d17')->nullable();
            $table->integer('d18')->nullable();
            $table->integer('d19')->nullable();
            $table->integer('d20')->nullable();
            $table->integer('d21')->nullable();
            $table->integer('d22')->nullable();
            $table->integer('d23')->nullable();
            $table->integer('d24')->nullable();
            $table->integer('d25')->nullable();
            $table->integer('d26')->nullable();
            $table->integer('d27')->nullable();
            $table->integer('d28')->nullable();
            $table->integer('d29')->nullable();
            $table->integer('d30')->nullable();
            $table->integer('d31')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal');
    }
};
