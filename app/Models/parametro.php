<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class parametro extends Model
{
    use HasFactory;

    // Campos que puedes llenar al crear o actualizar un registro
    protected $fillable = ['name', 'status', 'user_create_id'];

    // Evento de creación para asignar valores predeterminados
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($parametro) {
            // Asignar el estado por defecto al crear un nuevo parámetro
            $parametro->status = $parametro->status ?? 'Activo';
        });

        static::saving(function ($parametro) {
            // Convertir el nombre a mayúsculas antes de guardar
            $parametro->name = strtoupper($parametro->name);
        });
    }
}
