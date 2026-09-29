<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sasaran_strategis', function (Blueprint $table) {
            $table->id('id_sasaran_strategis');

            $table->unsignedBigInteger('id_tahun');

            $table->string('nama_sasaran_strategis', 255);

            $table->foreign('id_tahun')
                ->references('id_tahun')
                ->on('tahun');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sasaran_strategis');
    }
};