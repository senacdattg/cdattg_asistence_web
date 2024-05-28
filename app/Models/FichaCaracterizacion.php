<?php

namespace App\Models;

use App\Http\Controllers\InstructorController;
use App\Http\Controllers\UserController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FichaCaracterizacion extends Model
{
    use HasFactory;

    protected $fillable = ['ficha', 'nombre_curso',
    'codigo_programa',
    'horas_formacion',
    'cupo',
    'dias_de_formacion',
    'user_create_id',
    'user_edit_id',
    'regional_id',
    ];

    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }
    public function entradaSalida(){
        return $this->belongsTo(EntradaSalida::class);
    }

    public function userCreate()
    {
        return  $this->belongsTo(User::class, 'user_create_id');
    }
    public function userEdit()
    {
        return  $this->belongsTo(User::class, 'user_edit_id');
    }
    public function regional(){
        return $this->belongsTo(Regional::class);
    }

}
