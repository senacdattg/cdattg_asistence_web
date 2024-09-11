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
        $superAdministrador = Role::firstOrCreate(['name' => 'SUPER ADMINISTRADOR']);
        $administrador = Role::create(['name' => 'ADMINISTRADOR']);
        $instructor = Role::create(['name' => 'INSTRUCTOR']);

        // creacion de permisos
        // parametros
        $crearParametro = Permission::firstOrCreate(['name' => 'CREAR PARAMETRO']);
        $editarParametro = Permission::firstOrCreate(['name' => 'EDITAR PARAMETRO']);
        $verParametro = Permission::firstOrCreate(['name' => 'VER PARAMETRO']);
        $eliminarParametro = Permission::firstOrCreate(['name' => 'ELIMINAR PARAMETRO']);
        // temas
        $crearTema = Permission::firstOrCreate(['name' => 'CREAR TEMA']);
        $editarTema = Permission::firstOrCreate(['name' => 'EDITAR TEMA']);
        $verTema = Permission::firstOrCreate(['name' => 'VER TEMA']);
        $eliminarTema = Permission::firstOrCreate(['name' => 'ELIMINAR TEMA']);
        // regionales
        $crearRegional = Permission::firstOrCreate(['name' => 'CREAR REGIONAL']);
        $editarRegional = Permission::firstOrCreate(['name' => 'EDITAR REGIONAL']);
        $verRegional = Permission::firstOrCreate(['name' => 'VER REGIONAL']);
        $eliminarRegional = Permission::firstOrCreate(['name' => 'ELIMINAR REGIONAL']);
        // sedes
        $crearSede = Permission::firstOrCreate(['name' => 'CREAR SEDE']);
        $verSede = Permission::firstOrCreate(['name' => 'VER SEDE']);
        $editarSede = Permission::firstOrCreate(['name' => 'EDITAR SEDE']);
        $eliminarSede = Permission::firstOrCreate(['name' => 'ELIMINAR SEDE']);
        // bloques
        $crearBloque = Permission::firstOrCreate(['name' => 'CREAR BLOQUE']);
        $verBloque = Permission::firstOrCreate(['name' => 'VER BLOQUE']);
        $editarBloque = Permission::firstOrCreate(['name' => 'EDITAR BLOQUE']);
        $eliminarBloque = Permission::firstOrCreate(['name' => 'ELIMINAR BLOQUE']);
        // pisos
        $crearPiso = Permission::firstOrCreate(['name' => 'CREAR PISO']);
        $verPiso = Permission::firstOrCreate(['name' => 'VER PISO']);
        $editarPiso = Permission::firstOrCreate(['name' => 'EDITAR PISO']);
        $eliminarPiso = Permission::firstOrCreate(['name' => 'ELIMINAR PISO']);
        // ambientes
        $crearAmbiente = Permission::firstOrCreate(['name' => 'CREAR AMBIENTE']);
        $verAmbiente = Permission::firstOrCreate(['name' => 'VER AMBIENTE']);
        $editarAmbiente = Permission::firstOrCreate(['name' => 'EDITAR AMBIENTE']);
        $eliminarAmbiente = Permission::firstOrCreate(['name' => 'ELIMINAR AMBIENTE']);
        // instructores
        $crearInstructor = Permission::firstOrCreate(['name' => 'CREAR INSTRUCTOR']);
        $verInstructor = Permission::firstOrCreate(['name' => 'VER INSTRUCTOR']);
        $editarInstructor = Permission::firstOrCreate(['name' => 'EDITAR INSTRUCTOR']);
        $eliminarInstructor = Permission::firstOrCreate(['name' => 'ELIMINAR INSTRUCTOR']);
        // fichas de Caracterizacion
        $crearFichaCaracterizacion = Permission::firstOrCreate(['name' => 'CREAR FICHA DE CARACTERIZACION']);
        $editarFichaCaracterizacion = Permission::firstOrCreate(['name' => 'EDITAR FICHA DE CARACTERIZACION']);
        $verFichaCaracterizacion = Permission::firstOrCreate(['name' => 'VER FICHA DE CARACTERIZACION']);
        $eliminarFichaCaracterizacion = Permission::firstOrCreate(['name' => 'ELIMINAR FICHA DE CARACTERIZACION']);
        // roles y permisos
        $asignarPermisos = Permission::firstOrCreate(['name' => 'ASIGNAR PERMISOS']);

        // asistencia
        $tomarAsistencia = Permission::firstOrCreate(['name' => 'TOMAR ASISTENCIA']);

        // caracterizacion de programas
        $verProgramaCaracterizacion = Permission::firstOrCreate(['name' => 'VER PROGRAMA DE CARACTERIZACION']);
        $crearProgramaCaracterizacion = Permission::firstOrCreate(['name' => 'CREAR PROGRAMA DE CARACTERIZACION']);
        $editarProgramaCaracterizacion = Permission::firstOrCreate(['name' => 'EDITAR PROGRAMA DE CARACTERIZACION']);
        $eliminarProgramaCaracterizacion = Permission::firstOrCreate(['name' => 'ELIMINAR PROGRAMA DE CARACTERIZACION']);

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
            $verProgramaCaracterizacion,
            $crearProgramaCaracterizacion,  
            $editarProgramaCaracterizacion,
            $eliminarProgramaCaracterizacion,
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
        Schema::dropIfExists('roles');
    }
};
