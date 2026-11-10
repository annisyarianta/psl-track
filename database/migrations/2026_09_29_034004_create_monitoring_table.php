<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('monitoring', function (Blueprint $table) {
            $table->id('id_monitoring');

            $table->unsignedBigInteger('id_periode');
            $table->unsignedBigInteger('id_indikator_sub_kegiatan');

            $table->string('upaya', 255)->nullable();
            $table->string('capaian', 255)->nullable();

            $table->string('keterangan', 255)->nullable();
            $table->string('identifikasi', 255)->nullable();

            $table->unsignedBigInteger('last_updated_by');
            $table->timestamp('last_updated_at');

            $table->enum('status', [
                'notstarted',
                'onprogress',
                'done',
            ])->nullable();

            $table->foreign('id_periode')
                ->references('id_periode')
                ->on('periode');

            $table->foreign('id_indikator_sub_kegiatan')
                ->references('id_indikator_sub_kegiatan')
                ->on('indikator_sub_kegiatan');

            $table->foreign('last_updated_by')
                ->references('id_user')
                ->on('users');

            $table->unique([
                'id_periode',
                'id_indikator_sub_kegiatan'
            ]);

            $table->unique(['id_periode', 'id_indikator_sub_kegiatan'], 'monitoring_periode_indikator_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monitoring');
    }
};
