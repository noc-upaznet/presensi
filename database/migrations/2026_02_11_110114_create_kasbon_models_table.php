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
        Schema::create('kasbons', function (Blueprint $table) {
            $table->id();

            $table->foreignId('karyawan_id')
                ->constrained('data_karyawan')
                ->cascadeOnDelete();

            // nominal
            $table->decimal('total_kasbon', 15, 2);
            $table->decimal('kasbon_perbulan', 15, 2);
            $table->decimal('sisa_kasbon', 15, 2);

            // angsuran
            $table->integer('jumlah_angsuran');
            $table->integer('angsuran_ke')->default(0);

            // tanggal
            $table->date('tanggal_kasbon');
            $table->date('mulai_potong'); // mulai payroll potong
            $table->date('tanggal_lunas')->nullable();

            // status
            $table->enum('status', ['aktif', 'lunas', 'dibatalkan'])
                ->default('aktif');

            // catatan & approval
            $table->text('keterangan')->nullable();
            $table->foreignId('approved_by')->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kasbon_models');
    }
};
