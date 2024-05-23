<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    use HasFactory;
    protected $fillable = [
        'departamento',
        'pais_id',
        'status',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($departamento) {
            $departamento->departamento = strtoupper($departamento->departamento);
        });
    }
    public function municipios(){
        return $this->hasMany(Municipio::class);
    }
}
