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
        // database/migrations/xxxx_create_salary_slips_table.php
        Schema::create('salary_slips', function (Blueprint $table) {
            $table->id();
            $table->string('employee_name');
            $table->string('position');
            $table->string('period');
            $table->unsignedBigInteger('basic_salary');
            $table->unsignedBigInteger('allowance');
            $table->unsignedBigInteger('deduction');
            $table->unsignedBigInteger('total_salary');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_slips');
    }
};
