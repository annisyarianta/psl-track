<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kegiatan', function (Blueprint $table) {
            $table->id('id_kegiatan');

            $table->unsignedBigInteger('id_indikator_program');

            $table->string('nama_kegiatan', 255);

            $table->foreign('id_indikator_program')
                ->references('id_indikator_program')
                ->on('indikator_program');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kegiatan');
    }
};