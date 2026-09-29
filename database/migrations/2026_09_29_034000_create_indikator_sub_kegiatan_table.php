<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('indikator_sub_kegiatan', function (Blueprint $table) {
            $table->id('id_indikator_sub_kegiatan');

            $table->unsignedBigInteger('id_sub_kegiatan');

            $table->string('nama_indikator_sub_kegiatan', 255);
            $table->string('target_staff', 255)->nullable();

            $table->foreign('id_sub_kegiatan')
                ->references('id_sub_kegiatan')
                ->on('sub_kegiatan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('indikator_sub_kegiatan');
    }
};