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
        Schema::table('education_and_experience', function (Blueprint $table) {
            $table->dropColumn(['company', 'employment_period']);
        });

        Schema::rename('education_and_experience', 'education');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('education_and_experience', function (Blueprint $table) {
            //
        });
    }
};
