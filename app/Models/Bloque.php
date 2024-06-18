<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Expr\FuncCall;

class Bloque extends Model
{
    use HasFactory;

    protected $fillable = ['bloque', 'sede_id', 'user_create_id', 'user_edit_id', 'status'];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($bloque){
            $bloque->bloque = strtoupper($bloque->bloque);
        });
    }

    public function sede()
    {
        return $this->belongsTo(Sede::class);
    }
    public function piso()
    {
        return $this->hasMany(Piso::class);
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
