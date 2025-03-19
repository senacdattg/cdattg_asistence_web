<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CentroFormacion;

class CentroFormacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CentroFormacion::create([
            'regional_id'    => 1,
            'nombre'         => 'Centro de Desarrollo Agroindustrial, Turístico y Tecnológico del Guaviare Regional Guaviare',
            'telefono'       => '5840403',
            'direccion'      => 'KRA 19c N 16 34',
            'web'            => 'https://www.facebook.com/SENAGuaviare',
            'user_create_id' => 1,
            'user_update_id' => 1,
            'status'         => 1,
        ]);
    }
}
