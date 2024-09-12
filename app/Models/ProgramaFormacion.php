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

    


}
