<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // creacion de roles
        $superAdministrador = Role::create(['name' => 'SUPER ADMINISTRADOR']);
        $administrador = Role::create(['name' => 'ADMINISTRADOR']);
        $instructor = Role::create(['name' => 'INSTRUCTOR']);

        // creacion de permisos
        // sedes
        $crearSede = Permission::create(['name' => 'CREAR SEDE']);
        $verSede = Permission::create(['name' => 'VER SEDE']);
        $editarSede = Permission::create(['name' => 'EDITAR SEDE']);
        $eliminarSede = Permission::create(['name' => 'ELIMINAR SEDE']);
        // bloques
        $crearBloque = Permission::create(['name' => 'CREAR BLOQUE']);
        $verBloque = Permission::create(['name' => 'VER BLOQUE']);
        $editarBloque = Permission::create(['name' => 'EDITAR BLOQUE']);
        $eliminarBloque = Permission::create(['name' => 'ELIMINAR BLOQUE']);
        // pisos
        $crearPiso = Permission::create(['name' => 'CREAR PISO']);
        $verPiso = Permission::create(['name' => 'VER PISO']);
        $editarPiso = Permission::create(['name' => 'EDITAR PISO']);
        $eliminarPiso = Permission::create(['name' => 'ELIMINAR PISO']);
        // ambientes
        $crearAmbiente = Permission::create(['name' => 'CREAR AMBIENTE']);
        $verAmbiente = Permission::create(['name' => 'VER AMBIENTE']);
        $editarAmbiente = Permission::create(['name' => 'EDITAR AMBIENTE']);
        $eliminarAmbiente = Permission::create(['name' => 'ELIMINAR AMBIENTE']);
        // instructores
        $crearInstructor = Permission::create(['name' => 'CREAR INSTRUCTOR']);
        $verInstructor = Permission::create(['name' => 'VER INSTRUCTOR']);
        $editarInstructor = Permission::create(['name' => 'EDITAR INSTRUCTOR']);
        $eliminarInstructor = Permission::create(['name' => 'ELIMINAR INSTRUCTOR']);
        // asistencia
        $tomarAsistencia = Permission::create(['name' => 'TOMAR ASISTENCIA']);


        // asignacion de permisos
        $superAdministrador->givePermissionTo(
            $crearSede,
            $verSede,
            $editarSede,
            $eliminarSede,
            $crearBloque,
            $verBloque,
            $editarBloque,
            $eliminarBloque,
            $crearPiso,
            $verPiso,
            $editarPiso,
            $eliminarPiso,
            $crearAmbiente,
            $verAmbiente,
            $editarAmbiente,
            $eliminarAmbiente,
            $crearInstructor,
            $verInstructor,
            $editarInstructor,
            $eliminarInstructor,
            $tomarAsistencia
        );
        $administrador->givePermissionTo(
            $crearSede,
            $verSede,
            $editarSede,
            $eliminarSede,
            $crearBloque,
            $verBloque,
            $editarBloque,
            $eliminarBloque,
            $crearPiso,
            $verPiso,
            $editarPiso,
            $eliminarPiso,
            $crearAmbiente,
            $verAmbiente,
            $editarAmbiente,
            $eliminarAmbiente,
            $crearInstructor,
            $verInstructor,
            $editarInstructor,
            $eliminarInstructor
        );
        $instructor->givePermissionTo(
            $tomarAsistencia
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
