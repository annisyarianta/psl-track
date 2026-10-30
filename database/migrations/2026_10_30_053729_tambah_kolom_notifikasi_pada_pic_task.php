<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pic_task', function (Blueprint $table) {
            $table->timestamp('ditugaskan_pada')->nullable();
            $table->timestamp('dibaca_tugas_baru_pada')->nullable();
            $table->timestamp('dibaca_tenggat_3_hari_pada')->nullable();
            $table->timestamp('dibaca_tenggat_1_hari_pada')->nullable();
            $table->timestamp('dibaca_terlambat_pada')->nullable();
        });

        // Isi data lama agar task lama punya waktu penugasan
        DB::statement(
            'UPDATE pic_task
            JOIN task ON task.id_task = pic_task.id_task
            SET pic_task.ditugaskan_pada = task.assigned_at'
        );

        Schema::table('task', function (Blueprint $table) {
            $table->index('tenggat_waktu');
        });
    }

    public function down(): void
    {
        Schema::table('task', function (Blueprint $table) {
            $table->dropIndex(['tenggat_waktu']);
        });

        Schema::table('pic_task', function (Blueprint $table) {
            $table->dropColumn([
                'ditugaskan_pada',
                'dibaca_tugas_baru_pada',
                'dibaca_tenggat_3_hari_pada',
                'dibaca_tenggat_1_hari_pada',
                'dibaca_terlambat_pada',
            ]);
        });
    }
};
