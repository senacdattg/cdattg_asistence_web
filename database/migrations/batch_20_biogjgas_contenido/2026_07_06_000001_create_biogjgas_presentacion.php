<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('biogjgas_presentacion', function (Blueprint $table) {
            $table->id();
            $table->text('mision')->nullable();
            $table->text('vision')->nullable();
            $table->text('objetivo_general')->nullable();
            $table->longText('historia')->nullable();
            $table->string('video_url', 500)->nullable();
            $table->string('politicas_pdf', 500)->nullable();
            $table->json('equipo')->nullable();
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
        Schema::dropIfExists('biogjgas_presentacion');
    }
};
