<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoPrograma extends Model
{
    use HasFactory;

    public function programasFormacion()
    {
        return $this->hasMany(ProgramaFormacion::class);
    }
}
