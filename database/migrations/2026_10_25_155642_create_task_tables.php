<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('task', function (Blueprint $table) {
            $table->id('id_task');
            $table->unsignedBigInteger('id_program')->nullable();
            $table->string('deskripsi_tugas');
            $table->date('tenggat_waktu');
            $table->enum('status', ['todo', 'inprogress', 'done'])->default('todo');
            $table->unsignedBigInteger('assigned_by');
            $table->timestamp('assigned_at')->useCurrent();

            $table->foreign('id_program')->references('id_program')->on('program')->nullOnDelete();
            $table->foreign('assigned_by')->references('id_user')->on('users')->cascadeOnDelete();
        });

        Schema::create('pic_task', function (Blueprint $table) {
            $table->unsignedBigInteger('id_task');
            $table->unsignedBigInteger('id_user');

            $table->primary(['id_task', 'id_user']);
            $table->foreign('id_task')->references('id_task')->on('task')->cascadeOnDelete();
            $table->foreign('id_user')->references('id_user')->on('users')->cascadeOnDelete();
        });

        Schema::create('file_task', function (Blueprint $table) {
            $table->id('id_file_task');
            $table->unsignedBigInteger('id_task');
            $table->string('nama_file');
            $table->string('path_file');
            $table->unsignedBigInteger('uploaded_by');
            $table->timestamp('uploaded_at')->useCurrent();

            $table->foreign('id_task')->references('id_task')->on('task')->cascadeOnDelete();
            $table->foreign('uploaded_by')->references('id_user')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('file_task');
        Schema::dropIfExists('pic_task');
        Schema::dropIfExists('task');
    }
};
