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
        Schema::create('pic_indikator', function (Blueprint $table) {
            $table->id('id_pic_indikator');
            $table->unsignedBigInteger('id_indikator');
            $table->unsignedBigInteger('id_user');

            $table->foreign('id_indikator')->references('id_indikator')->on('indikator_program')->onDelete('cascade');
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pic_indikator');
    }
};
