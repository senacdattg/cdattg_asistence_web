<?php

namespace Database\Seeders;

use App\Models\Bloque;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BloqueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // SEDE CENTRO
        Bloque::create(['id' => 1, 'bloque' =>'CENTRO', 'sede_id' => 1, 'user_create_id' => 1, 'user_edit_id' => 1, 'status' => 1]);
        // SEDE BIODIVERSA
        Bloque::create(['id' => 2, 'bloque' => 'BIODIVERSA', 'sede_id' => 3, 'user_create_id' => 1, 'user_edit_id' => 1, 'status' => 1]);
        //SEDE MODELO
        Bloque::create(['id' => 3, 'bloque' => 'B1', 'sede_id' => 2, 'user_create_id' => 1, 'user_edit_id' => 1, 'status' => 1]);
        Bloque::create(['id' => 4, 'bloque' => 'B2', 'sede_id' => 2, 'user_create_id' => 1, 'user_edit_id' => 1, 'status' => 1]);
        Bloque::create(['id' => 5, 'bloque' => 'B3', 'sede_id' => 2, 'user_create_id' => 1, 'user_edit_id' => 1, 'status' => 1]);
        Bloque::create(['id' => 6, 'bloque' => 'B5', 'sede_id' => 2, 'user_create_id' => 1, 'user_edit_id' => 1, 'status' => 1]);
        Bloque::create(['id' => 7, 'bloque' => 'B6', 'sede_id' => 2, 'user_create_id' => 1, 'user_edit_id' => 1, 'status' => 1]);
        // SEDE AMBIENTE EXTERNO SAN JOSE
        Bloque::create(['id' => 8, 'bloque' => 'B1', 'sede_id' => 4, 'user_create_id' => 1, 'user_edit_id' => 1, 'status' => 1]);
    }
}
