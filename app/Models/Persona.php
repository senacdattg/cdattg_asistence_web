<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Persona extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'tipo_documento',
        'numero_documento',
        'primer_nombre',
        'segundo_nombre',
        'primer_apellido',
        'segundo_apellido',
        'fecha_de_nacimiento',
        'genero',
        'email',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($persona){
            $persona->primer_nombre = strtoupper($persona->primer_nombre);
            $persona->segundo_nombre = strtoupper($persona->segundo_nombre);
            $persona->primer_apellido = strtoupper($persona->primer_apellido);
            $persona->segundo_apellido = strtoupper($persona->segundo_apellido);




        });
    }


    public function user()
    {
        return $this->hasOne(User::class, 'persona_id');
    }
    public function tipoDocumento()
    {
        return $this->belongsTo(Parametro::class, 'tipo_documento');
    }
    public function tipoGenero()
    {
        return $this->belongsTo(Parametro::class, 'genero');
    }

    public function instructor()
    {
        return $this->hasOne(Instructor::class);
    }
    
    public function caracterizacionProgramas()
    {
        return $this->hasMany(CaracterizacionPrograma::class, 'instructor_id');
    }

}
