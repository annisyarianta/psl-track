<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('indikator_program', function (Blueprint $table) {
            $table->id('id_indikator');
            $table->unsignedBigInteger('id_program');
            $table->string('nama_indikator', 255);
            $table->string('target', 255);
            $table->enum('aspek', ['kualitas', 'kuantitas']);
            $table->string('periode_pengukuran', 255);
            $table->string('upaya', 255)->nullable();
            $table->dateTime('due_date')->nullable();

            $table->foreign('id_program')->references('id_program')->on('program')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('indikator_program');
    }
};
