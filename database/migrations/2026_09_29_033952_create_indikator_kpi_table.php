<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('indikator_kpi', function (Blueprint $table) {
            $table->id('id_indikator_kpi');

            $table->unsignedBigInteger('id_sasaran_strategis');

            $table->string('target_dirbag', 255)->nullable();
            $table->string('nama_indikator_kpi', 255);

            $table->foreign('id_sasaran_strategis')
                ->references('id_sasaran_strategis')
                ->on('sasaran_strategis');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('indikator_kpi');
    }
};