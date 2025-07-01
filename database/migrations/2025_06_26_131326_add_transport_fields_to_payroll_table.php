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
            $table->string('uang_makan')->default(0)->after('bpjs_jht');
            $table->string('transport')->default(0)->after('uang_makan');
            $table->string('fee_sharing')->default(0)->after('transport');
            $table->string('insentif')->default(0)->after('fee_sharing');
            $table->string('jml_psb')->default(0)->after('insentif');
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
