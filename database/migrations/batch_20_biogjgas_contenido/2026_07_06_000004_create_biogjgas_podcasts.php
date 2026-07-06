<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('biogjgas_podcasts', function (Blueprint $table) {
            $table->id();
            $table->string('titulo', 200);
            $table->text('descripcion')->nullable();
            $table->string('audio_url', 500)->nullable();
            $table->string('duracion', 20)->nullable();
            $table->string('invitados', 300)->nullable();
            $table->string('portada_path', 500)->nullable();
            $table->date('fecha')->nullable();
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
        Schema::dropIfExists('biogjgas_podcasts');
    }
};
