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
        Schema::table('ficha_caracterizacions', function (Blueprint $table) {
            $table->foreignId('municipio_id')->nullable()->constrained('municipios')->after('ambiente_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ficha_caracterizacions', function (Blueprint $table) {
            //
        });
    }
};
