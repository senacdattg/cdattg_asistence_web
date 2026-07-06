<?php

namespace App\Models\Biogjgas;

use App\Models\Biogjgas\Concerns\Publicable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BiogjgasPresentacion extends Model
{
    use Publicable, SoftDeletes;

    protected $table = 'biogjgas_presentacion';

    protected $fillable = [
        'mision', 'vision', 'objetivo_general', 'historia', 'video_url',
        'politicas_pdf', 'equipo', 'estado_publicacion', 'publicado_en',
        'user_create_id', 'user_update_id',
    ];

    protected $casts = [
        'equipo' => 'array',
        'publicado_en' => 'datetime',
    ];
}
