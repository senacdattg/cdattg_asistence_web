<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('biogjgas_lineas_investigacion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('semillero_id')->constrained('biogjgas_semilleros')->cascadeOnDelete();
            $table->string('nombre', 200);
            $table->text('descripcion')->nullable();
            $table->unsignedSmallInteger('orden')->default(0);
            $table->enum('estado_publicacion', ['borrador', 'publicado', 'archivado'])->default('borrador');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('biogjgas_integrantes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('semillero_id')->constrained('biogjgas_semilleros')->cascadeOnDelete();
            $table->string('nombre', 200);
            $table->string('rol', 100)->nullable();
            $table->string('programa', 150)->nullable();
            $table->unsignedSmallInteger('orden')->default(0);
            $table->enum('estado_publicacion', ['borrador', 'publicado', 'archivado'])->default('borrador');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('biogjgas_proyectos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('semillero_id')->constrained('biogjgas_semilleros')->cascadeOnDelete();
            $table->string('titulo', 200);
            $table->text('descripcion')->nullable();
            $table->enum('estado_ejecucion', ['en_ejecucion', 'finalizado'])->default('en_ejecucion');
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->unsignedSmallInteger('orden')->default(0);
            $table->enum('estado_publicacion', ['borrador', 'publicado', 'archivado'])->default('borrador');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('biogjgas_proyectos');
        Schema::dropIfExists('biogjgas_integrantes');
        Schema::dropIfExists('biogjgas_lineas_investigacion');
    }
};
