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
            'id' => 1,
            'tipo_documento' => NULL,
            'numero_documento' => 987654321,
            'primer_nombre' => 'PRIMER',
            'segundo_nombre' => NULL,
            'primer_apellido' => 'ADMIN',
            'segundo_apellido' => NULL,
            'fecha_de_nacimiento' => '2000-01-01',
            'genero' => NULL,
            'email' => 'admin@admin.com',
        ]);
        Persona::create([
            'id' => 2,
            'tipo_documento' => NULL,
            'numero_documento' => 123456789,
            'primer_nombre' => 'INSTRUCTOR',
            'segundo_nombre' => NULL,
            'primer_apellido' => 'PRUEBA',
            'segundo-apellido' => NULL,
            'fecha_de_nacimiento' => '2000-01-01',
            'genero' => NULL,
            'email' => 'instructor@instructor.com',

        ]);
    }
}
