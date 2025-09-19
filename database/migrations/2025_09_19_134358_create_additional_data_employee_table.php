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
        Schema::create('additional_data_employee', function (Blueprint $table) {
            $table->id();
            $table->integer('karyawan_id');
            $table->string('dress_size')->nullable();
            $table->string('shoe_size')->nullable();
            $table->string('height')->nullable();
            $table->string('weight')->nullable();
            $table->string('nip')->nullable();
            $table->date('start_date')->nullable();
            $table->string('personality')->nullable();
            $table->string('iq')->nullable();
            $table->string('parent_address')->nullable();
            $table->string('inlaw_address')->nullable();
            $table->string('history_of_illness')->nullable();
            $table->string('name_father_in_law')->nullable();
            $table->string('name_mother_in_law')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('additional_data_employee');
    }
};
