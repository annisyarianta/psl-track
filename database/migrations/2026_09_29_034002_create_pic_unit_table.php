<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pic_unit', function (Blueprint $table) {
            $table->id('id_pic_unit');

            $table->unsignedBigInteger('id_indikator_program');
            $table->unsignedBigInteger('id_unit');
            $table->unsignedBigInteger('assigned_by');

            $table->timestamp('assigned_at');

            $table->foreign('id_indikator_program')
                ->references('id_indikator_program')
                ->on('indikator_program');

            $table->foreign('id_unit')
                ->references('id_unit')
                ->on('unit');

            $table->foreign('assigned_by')
                ->references('id_user')
                ->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pic_unit');
    }
};