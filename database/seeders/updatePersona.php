<?php

namespace Database\Seeders;

use App\Models\Persona;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class updatePersona extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Persona::where('id',1)->update([
            'tipo_documento' => 8,
            'genero' => 11,
        ]);


        Persona::where('id', 2)->update([
            'tipo_documento' => 8,
            'genero' => 11,
        ]);
        Persona::where('id', 3)->update([
            'tipo_documento' => 8,
            'genero' => 11,
        ]);
    }
}
