<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Expr\FuncCall;

class EntradaSalida extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'aprendiz',
        'entrada',
        'salida',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
