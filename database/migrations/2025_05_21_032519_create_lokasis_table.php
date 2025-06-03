<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLokasisTable extends Migration
{
    public function up()
    {
        Schema::create('lokasis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lokasi');
            $table->string('koordinat')->nullable();
            $table->text('alamat')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lokasis');
    }
}