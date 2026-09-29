<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('indikator_kegiatan', function (Blueprint $table) {
            $table->id('id_indikator_kegiatan');

            $table->unsignedBigInteger('id_kegiatan');

            $table->string('nama_indikator_kegiatan', 255);
            $table->string('target_asmen', 255)->nullable();

            $table->foreign('id_kegiatan')
                ->references('id_kegiatan')
                ->on('kegiatan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('indikator_kegiatan');
    }
};