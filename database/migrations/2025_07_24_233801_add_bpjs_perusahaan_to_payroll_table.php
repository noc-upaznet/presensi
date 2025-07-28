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
        Schema::table('payroll', function (Blueprint $table) {
            $table->unsignedBigInteger('bpjs_perusahaan')->default(0)->after('bpjs_jht');
            $table->unsignedBigInteger('bpjs_jht_perusahaan')->default(0)->after('bpjs_perusahaan');
            $table->unsignedBigInteger('tunjangan_kebudayaan')->default(0)->after('lembur');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payroll', function (Blueprint $table) {
            //
        });
    }
};
