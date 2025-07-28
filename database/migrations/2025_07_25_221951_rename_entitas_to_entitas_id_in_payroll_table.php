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
            $table->renameColumn('entitas', 'entitas_id');
        });
        Schema::table('payroll', function (Blueprint $table) {
            $table->unsignedBigInteger('entitas_id')->change();

            $table->foreign('entitas_id')
                ->references('id')
                ->on('entitas')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payroll', function (Blueprint $table) {
            // Hapus foreign key dulu sebelum rename
            $table->dropForeign(['entitas_id']);
        });

        Schema::table('payroll', function (Blueprint $table) {
            $table->renameColumn('entitas_id', 'entitas');
        });
    }
};
