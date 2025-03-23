<?php

namespace Database\Seeders;

use App\Models\Persona;
use Illuminate\Database\Seeder;

class UpdatePersonasUserFieldsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Persona::with('user')->chunk(100, function ($personas) {
            foreach ($personas as $persona) {
                if ($persona->user) {
                    $persona->update([
                        'user_create_id' => 1,
                        'user_edit_id'   => 1,
                    ]);
                }
            }
        });
    }
}
