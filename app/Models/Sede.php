<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sede extends Model
{
    use HasFactory;
    protected $fillable = ['descripcion', 'direcion', 'ciudad', 'user_create_id', 'user_edit_id', 'status'];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($sede) {
            $sede->descripcion = strtoupper($sede->descripcion);
            $sede->direccion = strtoupper($sede->direccion);
            $sede->ciudad = strtoupper($sede->ciudad);
        });
    }

    public function userCreated()
    {
        return $this->belongsTo(User::class, 'user_create_id');
    }

    public function userEdited()
    {
        return $this->belongsTo(User::class, 'user_edit_id');
    }
    public function bloques()
    {
        return $this->hasMany(Bloque::class);
    }
}
