<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sede extends Model
{
    use HasFactory;
    protected $fillable = ['sede', 'direccion', 'user_create_id', 'user_edit_id', 'status', 'municipio_id', 'regional_id'];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($sede) {
            $sede->sede = strtoupper($sede->sede);
        });
    }

    public function userCreated()
    {
        return $this->belongsTo(User::class, 'user_create_id');
    }

    public function userEdited()
    {
        return $this->belongsTo(User::class, 'user_edit_id');
    }
    public function bloques()
    {
        return $this->hasMany(Bloque::class);
    }
    public function municipio()
    {
        return $this->belongsTo(Municipio::class);
    }
    public function regional(){
        return $this->belongsTo(Regional::class, 'regional_id');
    }

    public function programasFormacion()
    {
        return $this->hasMany(ProgramaFormacion::class, 'sede_id');
    }

    public function caracterizacionProgramas()
    {
        return $this->hasMany(CaracterizacionPrograma::class, 'sede_id');
    }
}
