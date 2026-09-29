<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('periode_tw', function (Blueprint $table) {
            $table->id('id_periode_tw');

            $table->unsignedBigInteger('id_tahun');

            $table->unsignedBigInteger('triwulan');

            $table->foreign('id_tahun')
                ->references('id_tahun')
                ->on('tahun');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('periode_tw');
    }
};