<?php

namespace App\Models\Biogjgas;

use App\Models\Biogjgas\Concerns\Publicable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BiogjgasRevistaEdicion extends Model
{
    use Publicable, SoftDeletes;

    protected $table = 'biogjgas_revista_ediciones';

    protected $fillable = [
        'slug', 'titulo', 'volumen', 'numero', 'anio', 'portada_path',
        'editorial', 'issn', 'articulos', 'fecha_publicacion', 'orden',
        'estado_publicacion', 'publicado_en', 'user_create_id', 'user_update_id',
    ];

    protected $casts = [
        'articulos' => 'array',
        'fecha_publicacion' => 'date',
        'publicado_en' => 'datetime',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
