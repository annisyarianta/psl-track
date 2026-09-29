<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sasaran_inisiatif', function (Blueprint $table) {
            $table->id('id_sasaran_inisiatif');

            $table->unsignedBigInteger('id_indikator_kpi');

            $table->string('nama_sasaran_inisiatif', 255);

            $table->foreign('id_indikator_kpi')
                ->references('id_indikator_kpi')
                ->on('indikator_kpi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sasaran_inisiatif');
    }
};