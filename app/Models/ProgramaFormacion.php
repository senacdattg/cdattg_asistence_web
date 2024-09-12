<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramaFormacion extends Model
{
    protected $table = 'programas_formacion';
    use HasFactory;

    public function ficha()
    {
        return $this->belongsTo(FichaCaracterizacion::class);
    }

    public function caracterizacionPrograma()
    {
        return $this->belongsTo(CaracterizacionPrograma::class);
    }

    public function sede()
    {
        return $this->belongsTo(Sede::class);
    }

   

    public function tipoPrograma()
    {
        return $this->belongsTo(TipoPrograma::class);
    }

}
