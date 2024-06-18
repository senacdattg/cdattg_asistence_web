<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Piso extends Model
{
    use HasFactory;
    protected $fillable = ['piso', 'bloque_id', 'user_create_id', 'user_edit_id', 'status'];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($piso) {
            $piso->piso = strtoupper($piso->piso);
        });
    }

    public function bloque()
    {
        return $this->belongsTo(Bloque::class);
    }
    public function ambientes()
    {
        return $this->hasMany(Ambiente::class, 'piso_id');
    }

    public function userCreate()
    {
        return $this->belongsTo(User::class, 'user_create_id');
    }

    public function userEdit()
    {
        return $this->belongsTo(User::class, 'user_edit_id');
    }
}
