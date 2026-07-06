<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('biogjgas_actividades', function (Blueprint $table) {
            $table->id();
            $table->string('titulo', 200);
            $table->string('tipo', 80)->nullable();
            $table->date('fecha')->nullable();
            $table->string('lugar', 200)->nullable();
            $table->string('modalidad', 50)->nullable();
            $table->longText('descripcion')->nullable();
            $table->foreignId('semillero_id')->nullable()->constrained('biogjgas_semilleros')->nullOnDelete();
            $table->enum('estado_actividad', ['programada', 'realizada', 'cancelada'])->default('programada');
            $table->unsignedSmallInteger('orden')->default(0);
            $table->enum('estado_publicacion', ['borrador', 'publicado', 'archivado'])->default('borrador');
            $table->timestamp('publicado_en')->nullable();
            $table->foreignId('user_create_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('user_update_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('biogjgas_actividades');
    }
};
