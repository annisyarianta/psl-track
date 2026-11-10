<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('periode', function (Blueprint $table) {
            $table->id('id_periode');
            $table->unsignedBigInteger('id_tahun');
            $table->enum('jenis_periode', ['bulanan', 'triwulan', 'semester', 'tahunan']);
            $table->unsignedTinyInteger('urutan_periode');

            $table->foreign('id_tahun')->references('id_tahun')->on('tahun'); 

            $table->unique(['id_tahun', 'jenis_periode', 'urutan_periode'], 'periode_tahun_jenis_urutan_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('periode');
    }
};
