<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramaFormacion extends Model
{
    use HasFactory;

    public function ficha()
    {
        return $this->hasMany(FichaCaracterizacion::class);
    }

    public function caracterizacionPrograma()
    {
        return $this->belongsTo(CaracterizacionPrograma::class);
    }

    public function sedes()
    {
        return $this->hasMany(Sede::class);
    }

    public function tipoPrograma()
    {
        return $this->belongsTo(TipoPrograma::class);
    }

}
