<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoPrograma extends Model
{
    use HasFactory;
    
    protected $table = 'tipos_programas';
   

    public function programasFormacion()
    {
        return $this->hasMany(ProgramaFormacion::class);
    }

    public function programaFormacion()
    {
        return $this->belongsTo(ProgramaFormacion::class);
    }

}
