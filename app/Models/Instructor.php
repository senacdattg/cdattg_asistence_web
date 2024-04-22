<?php

namespace App\Models;

use App\Http\Controllers\FichaCaracterizacionController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    use HasFactory;

    protected $fillable = [
        'persona_id',
    ];


    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id');
    }
    // public function fichasCaracterizacion()
    // {
    //     return $this->belongsTo(FichaCaracterizacionController::class. 'instructor_asignado');
    // }
}
