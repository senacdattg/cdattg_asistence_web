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

        
    }
}
