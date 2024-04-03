<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Expr\FuncCall;

class EntradaSalida extends Model
{
    use HasFactory;

    protected $fillable = [
        'fecha',
        'instructor_user_id',
        'aprendiz',
        'entrada',
        'salida',
        'ficha_caracterizacion_id',
        'listado',
    ];

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_user_id');
    }
    public function fichaCaracterizacion(){
        return $this->belongsTo(FichaCaracterizacion::class);
    }
}
