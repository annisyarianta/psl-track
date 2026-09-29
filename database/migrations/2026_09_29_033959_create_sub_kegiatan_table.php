<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sub_kegiatan', function (Blueprint $table) {
            $table->id('id_sub_kegiatan');

            $table->unsignedBigInteger('id_indikator_kegiatan');

            $table->string('nama_sub_kegiatan', 255);

            $table->foreign('id_indikator_kegiatan')
                ->references('id_indikator_kegiatan')
                ->on('indikator_kegiatan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sub_kegiatan');
    }
};