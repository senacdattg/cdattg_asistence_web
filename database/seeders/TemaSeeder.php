<?php

namespace Database\Seeders;

use App\Models\Tema;
use Illuminate\Database\Seeder;

class TemaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tema 1: ESTADOS
        $tema = Tema::create([
            'id'             => 1,
            'name'           => 'ESTADOS',
            'status'         => 1,
            'user_create_id' => 1,
            'user_edit_id'   => 1,
        ]);
        $tema->parametros()->sync([
            1 => ['status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1],
            2 => ['status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1],
        ]);

        // Tema 2: TIPO DE DOCUMENTO
        $tema = Tema::create([
            'id'             => 2,
            'name'           => 'TIPO DE DOCUMENTO',
            'status'         => 1,
            'user_create_id' => 1,
            'user_edit_id'   => 1,
        ]);
        $syncData = [];
        foreach (range(3, 8) as $id) {
            $syncData[$id] = ['status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1];
        }
        $tema->parametros()->sync($syncData);

        // Tema 3: GENERO
        $tema = Tema::create([
            'id'             => 3,
            'name'           => 'GENERO',
            'status'         => 1,
            'user_create_id' => 1,
            'user_edit_id'   => 1,
        ]);
        $syncData = [];
        foreach ([9, 10, 11] as $id) {
            $syncData[$id] = ['status' => 1, 'user_create_id' => 1, 'user_edit_id' => 1];
        }
        $tema->parametros()->sync($syncData);
    }
}
