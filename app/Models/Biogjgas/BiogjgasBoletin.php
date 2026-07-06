<?php

namespace App\Models\Biogjgas;

use App\Models\Biogjgas\Concerns\Publicable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BiogjgasBoletin extends Model
{
    use Publicable, SoftDeletes;

    protected $table = 'biogjgas_boletines';

    protected $fillable = [
        'titulo', 'numero', 'fecha', 'resumen', 'pdf_path', 'portada_path',
        'tematica', 'orden', 'estado_publicacion', 'publicado_en',
        'user_create_id', 'user_update_id',
    ];

    protected $casts = [
        'fecha' => 'date',
        'publicado_en' => 'datetime',
    ];
}
