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
        Schema::create('planes_mejoramiento', function (Blueprint $table) {
            $table->unsignedBigInteger('item_id')->primary();
            $table->foreign('item_id')->references('item_id')->on('item_evaluable')->onDelete('cascade');
            $table->unsignedBigInteger('aprendiz_id');
            $table->foreign('aprendiz_id')->references('id')->on('aprendices')->onDelete('cascade');
            $table->unsignedBigInteger('item_origen_id')->nullable();
            $table->foreign('item_origen_id')->references('item_id')->on('item_evaluable')->onDelete('cascade');            
            $table->boolean('estado')->default(true);
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
        Schema::dropIfExists('planes_mejoramiento');
    }
};
