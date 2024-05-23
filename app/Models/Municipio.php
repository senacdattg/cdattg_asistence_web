<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Expr\FuncCall;

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
    public function departamentos(){
        return $this->belongsTo(Departamento::class, 'departamento_id');
    }
}
