<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FichaCaracterizacion extends Model
{
    use HasFactory;

    protected $fillable = ['ficha', 'nombre_curso',
    'codigo_programa',
    'horas_formacion',
    'cupo',
    'dias_de_formacion',
    'instructor_asignado',
    'ambiente_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function municipio()
    {
        return $this->belongsTo(Municipio::class);
    }
    public function ambiente()
    {
        return $this->belongsTo(Ambiente::class);
    }
}
