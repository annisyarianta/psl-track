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
        Schema::create('monitoring', function (Blueprint $table) {
            $table->id('id_monitoring');
            $table->unsignedBigInteger('id_periode_tw');
            $table->unsignedBigInteger('id_indikator');
            $table->string('capaian', 255);
            $table->string('keterangan', 255)->nullable(); 
            $table->string('identifikasi', 255)->nullable();
            $table->unsignedBigInteger('last_updated_by')->nullable();
            $table->dateTime('last_updated_at')->nullable();
            $table->enum('status', ['notstarted', 'onprogress', 'done'])->nullable();

            $table->foreign('id_periode_tw')->references('id_periode_tw')->on('periode_tw')->onDelete('cascade');
            $table->foreign('id_indikator')->references('id_indikator')->on('indikator_program')->onDelete('cascade');
            $table->foreign('last_updated_by')->references('id_user')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitoring');
    }
};
