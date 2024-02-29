<?php

namespace Database\Seeders;

use App\Models\Persona;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PersonaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Persona::create([
            'tipo_documento' => 'sin documento',
            'numero_documento' => 987654321,
            'primer_nombre' => 'super',
            'segundo_nombre' => null,
            'primer_apellido' => 'admin',
            'segundo_apellido' => null,
            'fecha_de_nacimiento' => '2000-01-01',
            'genero' => 'sin definir',
            'email' => 'admin@admin.com',
            'cargo' => 'super admin',
        ]);
    }
}
