<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{

    public function up(): void
    {
        DB::statement("ALTER TABLE task MODIFY status ENUM('todo','inprogress','done','revisi') NOT NULL DEFAULT 'todo'");

        Schema::table('task', function (Blueprint $table) {
            $table->text('keterangan_revisi')->nullable()->after('hasil_tugas');
        });
    }

    public function down(): void
    {
        Schema::table('task', function (Blueprint $table) {
            $table->dropColumn('keterangan_revisi');
        });

        DB::statement("ALTER TABLE task MODIFY status ENUM('todo','inprogress','done') NOT NULL DEFAULT 'todo'");
    }
};
