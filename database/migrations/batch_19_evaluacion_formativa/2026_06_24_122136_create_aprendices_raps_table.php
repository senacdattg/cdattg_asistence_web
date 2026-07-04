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
        Schema::create('aprendices_raps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('aprendiz_id');
            $table->foreign('aprendiz_id')->references('id')->on('aprendices')->onDelete('cascade');
            $table->unsignedBigInteger('rap_id');
            $table->foreign('rap_id')->references('id')->on('resultados_aprendizajes')->onDelete('cascade');
            $table->unsignedBigInteger('estado_id');
            $table->foreign('estado_id')->references('id')->on('parametros_temas')->onDelete('cascade');
            $table->foreignId('user_create_id')->constrained('users');
            $table->foreignId('user_update_id')->nullable()->constrained('users');
            $table->foreignId('user_delete_id')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aprendices_raps');
    }
};
