<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsistenciaAprendiz extends Model
{
    use HasFactory;

    protected $table = 'asistencia_aprendices';

    protected $fillable = [
        'caracterizacion_id',
        'nombres',
        'apellidos',
        'numero_identificacion',
        'hora_ingreso',
    ];
    

    public function caracterizacion()
    {
        return $this->belongsTo(CaracterizacionPrograma::class, 'caracterizacion_id');
    }

    public function ficha()
    {
        return $this->hasOneThrough(FichaCaracterizacion::class, CaracterizacionPrograma::class, 'id', 'id', 'caracterizacion_id', 'ficha_id');
    }

    public function instructor()
    {
        return $this->hasOneThrough(Instructor::class, CaracterizacionPrograma::class, 'id', 'id', 'caracterizacion_id', 'instructor_id');
    }

    public function programa (){
        return $this->hasOneThrough(ProgramaFormacion::class, 'id', 'id', 'caracterizacion_id', 'programa_formacion_id'); 
    }

    public function jornada()
    {
        return $this->hasOneThrough(JornadaFormacion::class, 'id', 'id', 'caracterizacion_id', 'programa_id');
    }

    public function sede(){
        return $this->hasOneThrough(Sede::class, 'id', 'id', 'caracterizacion_id', 'sede_id'); 
    }
    
}
