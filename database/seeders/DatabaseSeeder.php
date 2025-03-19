<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            PersonaSeeder::class,
            UsersTableSeeder::class,
            PaisSeeder::class,
            RegionalSeeder::class,
            CentroFormacionSeeder::class,
            DepartamentoSeeder::class,
            MunicipioSeeder::class,
            SedeSeeder::class,
            BloqueSeeder::class,
            PisoSeeder::class,
            AmbienteSeeder::class,
            ParametroSeeder::class,
            TemaSeeder::class,
            UpdatePersonaSeeder::class,
            InstructorSeeder::class,
        ]);
    }
}
