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
        Schema::create('education_and_experience', function (Blueprint $table) {
            $table->id();
            $table->integer('karyawan_id');
            $table->string('level_of_education')->nullable();
            $table->string('institution')->nullable();
            $table->string('start_date')->nullable();
            $table->string('end_date')->nullable();
            $table->string('major')->nullable();
            $table->string('nilai')->nullable();
            $table->string('company')->nullable();
            $table->string('employment_period')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('education_and_experience');
    }
};
