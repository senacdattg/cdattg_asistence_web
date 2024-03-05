<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Expr\FuncCall;

class Bloque extends Model
{
    use HasFactory;

    protected $fillable = ['descripcion', 'sede_id', 'user_create_id', 'user_edit_id', 'status'];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($bloque){
            $bloque->descripcion = strtoupper($bloque->descripcion);
        });
    }

    public function sede()
    {
        return $this->belongsTo(Sede::class);
    }
    public function piso()
    {
        return $this->belongsTo(Piso::class);
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
