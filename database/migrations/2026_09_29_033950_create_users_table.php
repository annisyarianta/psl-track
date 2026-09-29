<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('id_user');

            $table->unsignedBigInteger('id_unit')->nullable();

            $table->string('nama', 255);
            $table->string('nopeg', 4);
            $table->string('email', 255);
            $table->string('password', 255);

            $table->enum('role', [
                'manager',
                'asmen',
                'staff',
            ]);

            $table->boolean('must_change_password')->default(true);

            $table->foreign('id_unit')
                ->references('id_unit')
                ->on('unit');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
