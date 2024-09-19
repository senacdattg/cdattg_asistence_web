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
}
