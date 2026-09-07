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
        Schema::create('periode_tw', function (Blueprint $table) {
            $table->id('id_periode_tw');
            $table->unsignedBigInteger('id_kpi');
            $table->integer('triwulan');

            $table->foreign('id_kpi')->references('id_kpi')->on('kpi')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periode_tw');
    }
};
