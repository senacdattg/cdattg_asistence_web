<?php

namespace Database\Seeders;

use App\Models\parametro;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ParametroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $parametros = [
            ['id' => 1, 'name' => 'ACTIVO', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1],
            ['id' => 2, 'name' => 'INACTIVO', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1],
            // tipos de documento
            ['id' => 3, 'name' => 'CEDULA DE CIUDADANIA', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1],
            ['id' => 4, 'name' => 'CEDULA DE EXTRANJERIA', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1],
            ['id' => 5, 'name' => 'PASAPORTE', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1],
            ['id' => 6, 'name' => 'TARJETA DE IDENTIDAD', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1],
            ['id' => 7, 'name' => 'REGISTRO CIVIL', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1],
            ['id' => 8, 'name' => 'SIN IDENTIFICACION', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1],
            // genero
            ['id' => 9, 'name' => 'MASCULINO', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1],
            ['id' => 10, 'name' => 'FEMENINO', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1],
            ['id' => 11, 'name' => 'NO DEFINE', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1],
        ];

        foreach ($parametros as $parametro) {
            parametro::create($parametro);
        }

    }
}
