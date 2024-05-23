<?php

namespace Database\Seeders;

use App\Models\Sede;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SedeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Sede::create(['id' => 1, 'sede' => 'CENTRO', 'direccion' => 'Cra 24 no. 7', 'user_create_id' => 1, 'user_edit_id' => 1, 'status' => 1, 'municipio_id' => 824, 'regional_id' => 1]);
        Sede::create(['id' => 2, 'sede' => 'MODELO', 'direccion' => 'Cra 19c no. 16-48', 'user_create_id' => 1, 'user_edit_id' => 1, 'status' => 1, 'municipio_id' => 824, 'regional_id' => 1]);
        Sede::create(['id' => 3, 'sede' => 'BIODIVERSA KM11', 'direccion' => 'KM 11 RUTA 65', 'user_create_id' => 1, 'user_edit_id' => 1, 'status' => 1, 'municipio_id' => 824, 'regional_id' => 1]);
        Sede::create(['id' => 4, 'sede' => 'AMBIENTE EXTERNO SAN JOSE', 'direccion' => 'BATLLON JOAQUIN PARIS', 'user_create_id' => 1, 'user_edit_id' => 1, 'status' => 1, 'municipio_id' => 824, 'regional_id' => 1]);
        Sede::create(['id' => 5, 'sede' => 'AMBIENTE EXTERNO CALAMAR', 'direccion' => 'CALAMAR', 'user_create_id' => 1, 'user_edit_id' => 1, 'status' => 1, 'municipio_id' => 142, 'regional_id' => 1]);
        Sede::create(['id' => 6, 'sede' => 'AMBIENTE EXTERNO EL RETORNO', 'direccion' => 'EL RETORNO', 'user_create_id' => 1, 'user_edit_id' => 1, 'status' => 1, 'municipio_id' => 315, 'regional_id' => 1]);
        Sede::create(['id' => 7, 'sede' => 'AMBIENTE EXTERNO MIRAFLORES', 'direccion' => 'MIRAFLORES', 'user_create_id' => 1, 'user_edit_id' => 1, 'status' => 1, 'municipio_id' => 557, 'regional_id' => 1]);
        Sede::create(['id' => 8, 'sede' => 'AMBIENTE EXTERNO MAPIRIPAN', 'direccion' => 'MAPIRIPAN', 'user_create_id' => 1, 'user_edit_id' => 1, 'status' => 1, 'municipio_id' => 536, 'regional_id' => 1]);
        Sede::create(['id' => 9, 'sede' => 'AMBIENTE EXTERNO PUERTO CONCORDIA', 'direccion' => 'PUERTO CONCORDIA', 'user_create_id' => 1, 'user_edit_id' => 1, 'status' => 1, 'municipio_id' => 699, 'regional_id' => 1]);
    }
}
