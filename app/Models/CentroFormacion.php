<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CentroFormacion extends Model
{
    
    protected $fillable = ['nombre', 'direccion', 'telefono', 'email', 'web', 'responsable', 'logo', 'estado'];

    public function regional()
    {
        return $this->belongsTo(Regional::class, 'regional_id');
    }
}
