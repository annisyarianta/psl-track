<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('indikator_inisiatif', function (Blueprint $table) {
            $table->id('id_indikator_inisiatif');

            $table->unsignedBigInteger('id_sasaran_inisiatif');

            $table->string('target_dirbag', 255)->nullable();
            $table->string('nama_indikator_inisiatif', 255);

            $table->foreign('id_sasaran_inisiatif')
                ->references('id_sasaran_inisiatif')
                ->on('sasaran_inisiatif');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('indikator_inisiatif');
    }
};