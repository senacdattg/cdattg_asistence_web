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
        Schema::create('parametros', function (Blueprint $table) {
            $table->id();
            // añadir columnas
            $table->string('name');  // Añadir la columna 'name'
            $table->enum('status', ['Activo', 'Inactivo']);  // Añadir la columna 'status'
            $table->unsignedBigInteger('user_create_id');  // Añadir la columna 'user_create_id'
            $table->unsignedBigInteger('user_edit_id');  // Añadir la columna 'edit_id'
            // termina añadir columnas
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parametros');
    }
};
