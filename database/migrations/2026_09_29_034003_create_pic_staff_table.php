<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pic_staff', function (Blueprint $table) {
            $table->id('id_pic_staff');

            $table->unsignedBigInteger('id_indikator_kegiatan');
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('assigned_by');

            $table->timestamp('assigned_at');

            $table->foreign('id_indikator_kegiatan')
                ->references('id_indikator_kegiatan')
                ->on('indikator_kegiatan');

            $table->foreign('id_user')
                ->references('id_user')
                ->on('users');

            $table->foreign('assigned_by')
                ->references('id_user')
                ->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pic_staff');
    }
};