<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('biogjgas_banners', function (Blueprint $table) {
            $table->id();
            $table->string('titulo', 200);
            $table->string('subtitulo', 300)->nullable();
            $table->string('imagen_path', 500)->nullable();
            $table->string('enlace', 500)->nullable();
            $table->unsignedSmallInteger('orden')->default(0);
            $table->date('vigente_desde')->nullable();
            $table->date('vigente_hasta')->nullable();
            $table->enum('estado_publicacion', ['borrador', 'publicado', 'archivado'])->default('borrador');
            $table->timestamp('publicado_en')->nullable();
            $table->foreignId('user_create_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('user_update_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('biogjgas_semilleros', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 50)->unique();
            $table->string('nombre', 200);
            $table->string('sigla', 20);
            $table->string('icono', 80)->default('fa-flask');
            $table->string('color_identidad', 20)->default('#0f9d58');
            $table->text('resumen')->nullable();
            $table->longText('descripcion')->nullable();
            $table->text('mision')->nullable();
            $table->text('vision')->nullable();
            $table->json('objetivos')->nullable();
            $table->string('instructor_lider', 200)->nullable();
            $table->string('correo_contacto', 150)->nullable();
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
        Schema::dropIfExists('biogjgas_semilleros');
        Schema::dropIfExists('biogjgas_banners');
    }
};
