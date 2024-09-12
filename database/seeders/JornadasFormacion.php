<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JornadasFormacion extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('jornadas_formacion')->insert([
            ['jornada' => 'Mañana', 'created_at' => now(), 'updated_at' => now()],
            ['jornada' => 'Tarde', 'created_at' => now(), 'updated_at' => now()],
            ['jornada' => 'Noche', 'created_at' => now(), 'updated_at' => now()],
            ['jornada' => 'Fin de Semana', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
