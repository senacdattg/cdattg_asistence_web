<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('temas', function (Blueprint $table) {
            $table->id();
            // añadir los nuevos campos
            $table->string('name')->unique();
            $table->boolean('status')->default(1);
            $table->foreignId('user_create_id')->constrained('users')->default(1);
            $table->foreignId('user_edit_id')->constrained('users')->default(1);
            // termina los nuevos campos
            $table->timestamps();
        });
        Schema::create('parametros_temas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tema_id');
            $table->unsignedBigInteger('parametro_id');
            $table->foreignId('user_create_id')->default(1)->constrained('users');
            $table->foreignId('user_edit_id')->default(1)->constrained('users');
            $table->boolean('status')->default(1);
            $table->timestamps();

            $table->foreign('tema_id')->references('id')->on('temas');
            $table->foreign('parametro_id')->references('id')->on('parametros');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temas');
    }
};
