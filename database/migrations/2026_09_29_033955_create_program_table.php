<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('program', function (Blueprint $table) {
            $table->id('id_program');

            $table->unsignedBigInteger('id_indikator_inisiatif');

            $table->string('nama_program', 255);

            $table->foreign('id_indikator_inisiatif')
                ->references('id_indikator_inisiatif')
                ->on('indikator_inisiatif');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program');
    }
};