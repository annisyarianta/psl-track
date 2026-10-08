<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('indikator_kpi', function (Blueprint $table) {
            $table->renameColumn('target_dirbag', 'target_dirut');
        });
    }

    public function down(): void
    {
        Schema::table('indikator_kpi', function (Blueprint $table) {
            $table->renameColumn('target_dirut', 'target_dirbag');
        });
    }
};