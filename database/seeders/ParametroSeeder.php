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
            // roles
            ['id' => 12, 'name' => 'APRENDIZ', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1],
            ['id' => 13, 'name' => 'ADMINISTRADOR', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1],
            ['id' => 14, 'name' => 'INSTRUCTOR', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1],
            ['id' => 15, 'name' => 'ADMINISTRATIVO', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1],
            ['id' => 16, 'name' => 'VISITANTE', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1],
            ['id' => 17, 'name' => 'SUPER ADMINISTRADOR', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1],
            ['id' => 18, 'name' => 'SUPERVISOR', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1],
            ['id' => 19, 'name' => 'VIGILANTE', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1],
            // tipo vehiculo
            ['id' => 20, 'name' => 'AUTOMOVIL', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1],
            ['id' => 21, 'name' => 'MOTOCICLETA', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1],
            ['id' => 22, 'name' => 'BICICLETA', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1],
            // tipo dispositivo
            ['id' => 23, 'name' => 'COMPUTADOR PORTATIL', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1],
            ['id' => 24, 'name' => 'TABLET', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1],
            // Marcas de computadoras
            ['id' => 25, 'name' => 'LENOVO', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1],
            ['id' => 26, 'name' => 'HP', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1],
            ['id' => 27, 'name' => 'DELL', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1],
            ['id' => 28, 'name' => 'APPLE', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1],
            ['id' => 29, 'name' => 'ACER', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1],
            ['id' => 30, 'name' => 'TOSHIBA', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1],
            ['id' => 31, 'name' => 'ASUS', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1],
            ['id' => 32, 'name' => 'SONY', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1],
            ['id' => 33, 'name' => 'LG', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1],
            ['id' => 34, 'name' => 'ALIENWARE', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1],
            ['id' => 35, 'name' => 'SAMSUNG', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1],
            // Marcas de vehículos
            ['id' => 36, 'name' => 'RENAULT', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1],
            ['id' => 37, 'name' => 'CHEVROLET', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1],
            ['id' => 38, 'name' => 'SUZUKI', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1],
            ['id' => 39, 'name' => 'KIA', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1],
            ['id' => 40, 'name' => 'TOYOTA', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1],
            ['id' => 41, 'name' => 'FOTON', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1],
            ['id' => 42, 'name' => 'BYD', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1],
            ['id' => 43, 'name' => 'GREAT WALL', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1],
            ['id' => 44, 'name' => 'JAC', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1],
            ['id' => 45, 'name' => 'JMC', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1],
            ['id' => 46, 'name' => 'DFSK', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1],
            ['id' => 47, 'name' => 'CHANGAN', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1],
            ['id' => 48, 'name' => 'AKT', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1],
            ['id' => 49, 'name' => 'APOLLO MOTORS', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1],
            ['id' => 50, 'name' => 'APRILIA', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1],
            ['id' => 51, 'name' => 'AYCO', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1],
            ['id' => 52, 'name' => 'BAJAJ', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1],
            ['id' => 53, 'name' => 'BENELLI', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1],
            ['id' => 54, 'name' => 'BMW', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1],
            ['id' => 55, 'name' => 'CF MOTO', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1],
            ['id' => 56, 'name' => 'HONDA', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1],
            ['id' => 57, 'name' => 'VICTORY', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1],
            ['id' => 58, 'name' => 'YAMAHA', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1],
            ['id' => 59, 'name' => 'TVS', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1],
            ['id' => 60, 'name' => 'AUTECO', 'status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1],
            //profesiones
        ];

        foreach ($parametros as $parametro) {
            parametro::create($parametro);
        }

    }
}
