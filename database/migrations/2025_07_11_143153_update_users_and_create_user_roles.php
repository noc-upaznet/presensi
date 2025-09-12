<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // public function up(): void
    // {
    //     // Tambah kolom current_role di users
    //     Schema::table('users', function (Blueprint $table) {
    //         $table->string('current_role')->nullable()->after('email');
    //         $table->dropColumn('role'); // Hapus enum role lama
    //     });

    //     // Buat tabel user_roles
    //     Schema::create('user_roles', function (Blueprint $table) {
    //         $table->id();
    //         $table->foreignId('user_id')->constrained()->onDelete('cascade');
    //         $table->string('role'); // e.g. admin, spv, hr
    //         $table->timestamps();
    //     });
    // }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback: Tambah kembali role lama
        // Schema::table('users', function (Blueprint $table) {
        //     $table->enum('role', ['admin', 'user', 'hr', 'spv', 'spv_noc', 'spv_teknisi', 'spv_sm'])->nullable();
        //     $table->dropColumn('current_role');
        // });
    }
};
