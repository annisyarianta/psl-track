<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('file_pelaporan', function (Blueprint $table) {
            $table->id('id_file');

            $table->unsignedBigInteger('id_monitoring');

            $table->string('nama_file', 255);
            $table->string('path_file', 255);

            $table->unsignedBigInteger('uploaded_by');
            $table->timestamp('uploaded_at');

            $table->foreign('id_monitoring')
                ->references('id_monitoring')
                ->on('monitoring');

            $table->foreign('uploaded_by')
                ->references('id_user')
                ->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('file_pelaporan');
    }
};