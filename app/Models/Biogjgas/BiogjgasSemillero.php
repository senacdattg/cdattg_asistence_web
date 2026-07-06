<?php

namespace App\Models\Biogjgas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BiogjgasSemillero extends Model
{
    use SoftDeletes;

    protected $table = 'biogjgas_semilleros';

    protected $fillable = [
        'slug',
        'nombre',
        'sigla',
        'icono',
        'color_identidad',
        'resumen',
        'descripcion',
        'mision',
        'vision',
        'objetivos',
        'instructor_lider',
        'correo_contacto',
        'orden',
        'estado_publicacion',
        'publicado_en',
        'user_create_id',
        'user_update_id',
    ];

    protected $casts = [
        'objetivos' => 'array',
        'publicado_en' => 'datetime',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopePublicados($query)
    {
        return $query->where('estado_publicacion', 'publicado');
    }

    public function lineas()
    {
        return $this->hasMany(BiogjgasLineaInvestigacion::class, 'semillero_id');
    }

    public function integrantes()
    {
        return $this->hasMany(BiogjgasIntegrante::class, 'semillero_id');
    }

    public function proyectos()
    {
        return $this->hasMany(BiogjgasProyecto::class, 'semillero_id');
    }
}
