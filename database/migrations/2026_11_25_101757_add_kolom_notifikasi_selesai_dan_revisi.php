<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('task', function (Blueprint $table) {
            $table->unsignedBigInteger('diselesaikan_oleh')->nullable();
            $table->timestamp('diselesaikan_pada')->nullable();
            $table->timestamp('dibaca_selesai_pada')->nullable();
            $table->timestamp('direvisi_pada')->nullable();
        });

        Schema::table('pic_task', function (Blueprint $table) {
            $table->timestamp('dibaca_revisi_pada')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('pic_task', function (Blueprint $table) {
            $table->dropColumn('dibaca_revisi_pada');
        });

        Schema::table('task', function (Blueprint $table) {
            $table->dropColumn([
                'diselesaikan_oleh',
                'diselesaikan_pada',
                'dibaca_selesai_pada',
                'direvisi_pada',
            ]);
        });
    }
};
