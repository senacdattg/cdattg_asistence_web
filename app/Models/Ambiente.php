<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ambiente extends Model
{
    use HasFactory;
    protected $fillable = ['descripcion', 'piso_id', 'user_create_id', 'user_edit_id', 'status'];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($ambiente) {
            $ambiente->descripcion = strtoupper($ambiente->descripcion);
        });
    }
    
    public function piso()
    {
        return $this->belongsTo(Piso::class, 'piso_id');
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
