<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FichaCaracterizacion extends Model
{
    use HasFactory;
    protected $fillable = [
        'programa_formacion_id',
        'instructor_id',
        'ficha',
        
    ];

    public function instructores()
    {
        return $this->hasMany(Instructor::class);
    }

    public function programaFormacion()
    {
        return $this->belongsTo(ProgramaFormacion::class);
    }

    public function caracterizacionPrograma()
    {
        return $this->belongsTo(CaracterizacionPrograma::class);
    }
}
