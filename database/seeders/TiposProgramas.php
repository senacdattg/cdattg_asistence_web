<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TiposProgramas extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tipos_programas')->insert([
            ['nombre' => 'tecnologo'],
            ['nombre' => 'tecnico'],
            ['nombre' => 'operario'],
            ['nombre' => 'complementario'],
        ]);
    }
}
