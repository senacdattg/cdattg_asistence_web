<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    use HasFactory;
    protected $fillable = [
        'municipio',
        'departamento_id',
        'status',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($municipio) {
            $municipio->municipio = strtoupper($municipio->municipio);
        });
    }




    public function fichaCaracterizacion()
    {
        return $this->hasMany(FichaCaracterizacion::class);
    }
    public function sedes()
    {
        return $this->hasMany(Sede::class);
    }
}
