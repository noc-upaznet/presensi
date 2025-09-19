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
        Schema::create('member_family_of_kk', function (Blueprint $table) {
            $table->id();
            $table->integer('karyawan_id');
            $table->string('relationships')->nullable();
            $table->string('name')->nullable();
            $table->string('nik')->nullable();
            $table->string('gender')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('religion')->nullable();
            $table->string('education')->nullable();
            $table->string('marital_status')->nullable();
            $table->date('wedding_date')->nullable();
            $table->string('relationship_in_family')->nullable();
            $table->string('citizenship')->nullable();
            $table->string('father')->nullable();
            $table->string('mother')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_family_of_kk');
    }
};
