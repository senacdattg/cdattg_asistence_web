<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Crear roles usando firstOrCreate para evitar duplicados
        $superAdministrador = Role::firstOrCreate(['name' => 'SUPER ADMINISTRADOR']);
        $administrador      = Role::firstOrCreate(['name' => 'ADMINISTRADOR']);
        $instructor         = Role::firstOrCreate(['name' => 'INSTRUCTOR']);

        // Definir un arreglo de permisos para cada grupo
        $permisos = [
            // Permisos para parámetros
            'CREAR PARAMETRO',
            'EDITAR PARAMETRO',
            'VER PARAMETRO',
            'ELIMINAR PARAMETRO',
            // Permisos para temas
            'CREAR TEMA',
            'EDITAR TEMA',
            'VER TEMA',
            'ELIMINAR TEMA',
            'ELIMINAR PARAMETRO DE TEMA',
            // Permisos para regionales
            'CREAR REGIONAL',
            'EDITAR REGIONAL',
            'VER REGIONAL',
            'ELIMINAR REGIONAL',
            // Permisos para sedes
            'CREAR SEDE',
            'VER SEDE',
            'EDITAR SEDE',
            'ELIMINAR SEDE',
            // Permisos para bloques
            'CREAR BLOQUE',
            'VER BLOQUE',
            'EDITAR BLOQUE',
            'ELIMINAR BLOQUE',
            // Permisos para pisos
            'CREAR PISO',
            'VER PISO',
            'EDITAR PISO',
            'ELIMINAR PISO',
            // Permisos para ambientes
            'CREAR AMBIENTE',
            'VER AMBIENTE',
            'EDITAR AMBIENTE',
            'ELIMINAR AMBIENTE',
            // Permisos para instructores
            'CREAR INSTRUCTOR',
            'VER INSTRUCTOR',
            'EDITAR INSTRUCTOR',
            'ELIMINAR INSTRUCTOR',
            // Permisos para fichas de caracterización
            'CREAR FICHA DE CARACTERIZACION',
            'EDITAR FICHA DE CARACTERIZACION',
            'VER FICHA DE CARACTERIZACION',
            'ELIMINAR FICHA DE CARACTERIZACION',
            // Permisos para roles y permisos
            'ASIGNAR PERMISOS',
            // Permisos para asistencia
            'TOMAR ASISTENCIA',
            // Permisos para caracterización de programas
            'VER PROGRAMA DE CARACTERIZACION',
            'CREAR PROGRAMA DE CARACTERIZACION',
            'EDITAR PROGRAMA DE CARACTERIZACION',
            'ELIMINAR PROGRAMA DE CARACTERIZACION',
            'MANIPULAR CARACTERIZACION',
        ];

        // Crear cada permiso si no existe
        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(['name' => $permiso]);
        }

        // Asignar permisos a SUPER ADMINISTRADOR (todos los permisos que quieras para este rol)
        $superAdministrador->givePermissionTo([
            'CREAR PARAMETRO',
            'EDITAR PARAMETRO',
            'VER PARAMETRO',
            'ELIMINAR PARAMETRO',
            'CREAR TEMA',
            'EDITAR TEMA',
            'VER TEMA',
            'ELIMINAR TEMA',
            'ELIMINAR PARAMETRO DE TEMA',
            'CREAR REGIONAL',
            'EDITAR REGIONAL',
            'VER REGIONAL',
            'ELIMINAR REGIONAL',
            'CREAR SEDE',
            'VER SEDE',
            'EDITAR SEDE',
            'ELIMINAR SEDE',
            'CREAR BLOQUE',
            'VER BLOQUE',
            'EDITAR BLOQUE',
            'ELIMINAR BLOQUE',
            'CREAR PISO',
            'VER PISO',
            'EDITAR PISO',
            'ELIMINAR PISO',
            'CREAR AMBIENTE',
            'VER AMBIENTE',
            'EDITAR AMBIENTE',
            'ELIMINAR AMBIENTE',
            'ASIGNAR PERMISOS',
            'VER PROGRAMA DE CARACTERIZACION',
            'CREAR PROGRAMA DE CARACTERIZACION',
            'EDITAR PROGRAMA DE CARACTERIZACION',
            'ELIMINAR PROGRAMA DE CARACTERIZACION',
            'MANIPULAR CARACTERIZACION',
        ]);

        // Asignar permisos al rol ADMINISTRADOR
        $administrador->givePermissionTo([
            'CREAR FICHA DE CARACTERIZACION',
            'EDITAR FICHA DE CARACTERIZACION',
            'VER FICHA DE CARACTERIZACION',
            'ELIMINAR FICHA DE CARACTERIZACION',
            'CREAR INSTRUCTOR',
            'VER INSTRUCTOR',
            'EDITAR INSTRUCTOR',
            'ELIMINAR INSTRUCTOR',
        ]);

        // Asignar permisos al rol INSTRUCTOR
        $instructor->givePermissionTo([
            'TOMAR ASISTENCIA',
        ]);
    }
}
