<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('task', function (Blueprint $table) {
            $table->string('judul_tugas')->after('id_program');
        });

        DB::table('task')->update([
            'judul_tugas' => DB::raw('LEFT(deskripsi_tugas, 100)'),
        ]);
    }

    public function down(): void
    {
        Schema::table('task', function (Blueprint $table) {
            $table->dropColumn('judul_tugas');
        });
    }
};
