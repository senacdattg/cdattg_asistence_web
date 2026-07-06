<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('biogjgas_revista_ediciones', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 80)->unique();
            $table->string('titulo', 200);
            $table->unsignedSmallInteger('volumen')->nullable();
            $table->unsignedSmallInteger('numero')->nullable();
            $table->unsignedSmallInteger('anio');
            $table->string('portada_path', 500)->nullable();
            $table->longText('editorial')->nullable();
            $table->string('issn', 30)->nullable();
            $table->json('articulos')->nullable();
            $table->date('fecha_publicacion')->nullable();
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
        Schema::dropIfExists('biogjgas_revista_ediciones');
    }
};
