<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('indikator_program', function (Blueprint $table) {
            $table->id('id_indikator_program');

            $table->unsignedBigInteger('id_program');

            $table->string('nama_indikator_program', 255);
            $table->string('target_manager', 255)->nullable();

            $table->enum('aspek', [
                'kualitas',
                'kuantitas',
            ]);

            $table->enum('periode_pengukuran', [
                'triwulan',
                'semester',
                'tahunan',
            ]);

            $table->foreign('id_program')
                ->references('id_program')
                ->on('program');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('indikator_program');
    }
};