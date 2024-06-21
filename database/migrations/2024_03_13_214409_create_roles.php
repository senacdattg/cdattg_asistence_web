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
        // parametros
        $crearParametro = Permission::create(['name' => 'CREAR PARAMETRO']);
        $editarParametro = Permission::create(['name' => 'EDITAR PARAMETRO']);
        $verParametro = Permission::create(['name' => 'VER PARAMETRO']);
        $eliminarParametro = Permission::create(['name' => 'ELIMINAR PARAMETRO']);
        // temas
        $crearTema = Permission::create(['name' => 'CREAR TEMA']);
        $editarTema = Permission::create(['name' => 'EDITAR TEMA']);
        $verTema = Permission::create(['name' => 'VER TEMA']);
        $eliminarTema = Permission::create(['name' => 'ELIMINAR TEMA']);
        // regionales
        $crearRegional = Permission::create(['name' => 'CREAR REGIONAL']);
        $editarRegional = Permission::create(['name' => 'EDITAR REGIONAL']);
        $verRegional = Permission::create(['name' => 'VER REGIONAL']);
        $eliminarRegional = Permission::create(['name' => 'ELIMINAR REGIONAL']);
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
        // fichas de Caracterizacion
        $crearFichaCaracterizacion = Permission::create(['name' => 'CREAR FICHA DE CARACTERIZACION']);
        $editarFichaCaracterizacion = Permission::create(['name' => 'EDITAR FICHA DE CARACTERIZACION']);
        $verFichaCaracterizacion = Permission::create(['name' => 'VER FICHA DE CARACTERIZACION']);
        $eliminarFichaCaracterizacion = Permission::create(['name' => 'ELIMINAR FICHA DE CARACTERIZACION']);
        // roles y permisos
        $asignarPermisos = permission::create(['name' => 'ASIGNAR PERMISOS']);

        // asistencia
        $tomarAsistencia = Permission::create(['name' => 'TOMAR ASISTENCIA']);


        // asignacion de permisos
        $superAdministrador->givePermissionTo(
            $crearParametro,
            $editarParametro,
            $verParametro,
            $eliminarParametro,
            $crearTema,
            $editarTema,
            $verTema,
            $eliminarTema,
            $crearRegional,
            $editarRegional,
            $verRegional,
            $eliminarRegional,
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
            $asignarPermisos,
        );
        $administrador->givePermissionTo(
            $crearFichaCaracterizacion,
            $editarFichaCaracterizacion,
            $verFichaCaracterizacion,
            $eliminarFichaCaracterizacion,
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
