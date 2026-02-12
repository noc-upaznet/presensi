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
        Schema::create('kasbon_details', function (Blueprint $table) {
            $table->id();

            $table->foreignId('kasbon_id')
                ->constrained('kasbons')
                ->cascadeOnDelete();

            $table->date('periode'); // contoh: 2026-02-01
            $table->decimal('nominal_potong', 15, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kasbon_details');
    }
};
