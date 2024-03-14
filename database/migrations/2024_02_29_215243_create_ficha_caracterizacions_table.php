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
        Schema::create('ficha_caracterizacions', function (Blueprint $table) {
            $table->id();
            $table->string('ficha')->nullable();
            $table->string('nombre_curso');
            $table->string('codigo_programa')->nullable();
            $table->integer('horas_formacion')->nullable();
            $table->integer('cupo')->nullable();
            $table->string('dias_de_formacion')->nullable();
            $table->foreignId('municipio_id')->nullable();
            $table->foreignId('instructor_asignado')->constrained('users');
            $table->foreignId('ambiente_id')->constrained('ambientes');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ficha_caracterizacions');
    }
};
