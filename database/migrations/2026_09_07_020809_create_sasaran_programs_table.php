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
        Schema::create('sasaran_program', function (Blueprint $table) {
            $table->id('id_sasaran');
        $table->unsignedBigInteger('id_kpi');
        $table->string('nama_sasaran', 255);

        $table->foreign('id_kpi')->references('id_kpi')->on('kpi')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sasaran_program');
    }
};
