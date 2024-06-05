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
        Schema::create('ficha_caracterizacions_instructors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ficha_id')->constrained('ficha_caracterizacions');
            $table->foreignId('instructor_id')->constrained('instructors');
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ficha_caracterizacions_instructors', function (Blueprint $table) {
            Schema::dropIfExists('ficha_caracterizacions_instructors');
        });
    }
};
