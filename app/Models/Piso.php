<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Piso extends Model
{
    use HasFactory;
    protected $fillable = ['descripcion', 'bloque_id', 'user_create_id', 'user_edit_id', 'status'];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($piso) {
            $piso->descripcion = strtoupper($piso->descripcion);
        });
    }

    public function bloque()
    {
        return $this->belongsTo(Bloque::class);
    }
    public function ambiente()
    {
        return $this->belongsTo(Ambiente::class);
    }

    public function userCreated()
    {
        return $this->belongsTo(User::class, 'user_create_id');
    }

    public function userEdited()
    {
        return $this->belongsTo(User::class, 'user_edit_id');
    }
}
