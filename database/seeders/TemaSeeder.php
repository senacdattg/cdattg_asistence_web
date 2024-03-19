<?php

namespace Database\Seeders;

use App\Models\Tema;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TemaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tema = Tema::create(['id' => 1, 'name' => 'ESTADOS', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1]);
        $tema->parametros()->sync([1, 2], 1);

        $tema = Tema::create(['id' => 2, 'name' => 'TIPO DE DOCUMENTO', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1]);
        $tema->parametros()->sync(range(3, 8), 1);

        $tema = Tema::create(['id' => 3, 'name' => 'GENERO', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1]);
        $tema->parametros()->sync([9, 10, 11], 1);

        $tema = Tema::create(['id' => 4, 'name' => 'ROLES', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1]);
        $tema->parametros()->sync(range(12, 19), 1);

        $tema = Tema::create(['id' => 5, 'name' => 'TIPO DE VEHICULO', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1]);
        $tema->parametros()->sync(range(20, 22), 1);

        $tema = Tema::create(['id' => 6, 'name' => 'TIPO DE DISPOSITIVO', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1]);
        $tema->parametros()->sync(range(23, 24), 1);

        $tema = Tema::create(['id' => 7, 'name' => 'MARCAS DE COMPUTADORES', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1]);
        $tema->parametros()->sync(range(25, 35), 1);

        $tema = Tema::create(['id' => 8, 'name' => 'MARCAS DE VEHICULOS', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1]);
        $tema->parametros()->sync(range(36, 60), 1);

    }
}
