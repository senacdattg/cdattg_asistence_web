<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaracterizacionPrograma extends Model
{
    use HasFactory;

    protected $filler = [
        'tipo_programa_id',
        'sede_id',
        'nombre',
        'duracion',
    ];

    public function ficha()
    {
        return $this->hasOne(FichaCaracterizacion::class);
    }

    public function instructores()
    {
        return $this->hasMany(Instructor::class);
    }

    public function programasFormacion()
    {
        return $this->hasMany(ProgramaFormacion::class);
    }

    public function sedes()
    {
        return $this->hasMany(Sede::class);
    }

}
