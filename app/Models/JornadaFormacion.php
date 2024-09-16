<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JornadaFormacion extends Model
{
    use HasFactory;
    protected $table = 'jornadas_formacion'; 


    public function caracterizaciones()
    {
        return $this->hasMany(CaracterizacionPrograma::class);
    }

}
