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
        Schema::create('file_pelaporan', function (Blueprint $table) {
            $table->id('id_file');
            $table->unsignedBigInteger('id_monitoring');
            $table->string('nama_file', 255);
            $table->string('path_file', 255);
            $table->unsignedBigInteger('uploaded_by')->nullable();
            $table->dateTime('uploaded_at')->nullable();

            $table->foreign('id_monitoring')->references('id_monitoring')->on('monitoring')->onDelete('cascade');
            $table->foreign('uploaded_by')->references('id_user')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_pelaporan');
    }
};
