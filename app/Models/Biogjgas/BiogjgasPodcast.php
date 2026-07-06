<?php

namespace App\Models\Biogjgas;

use App\Models\Biogjgas\Concerns\Publicable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BiogjgasPodcast extends Model
{
    use Publicable, SoftDeletes;

    protected $table = 'biogjgas_podcasts';

    protected $fillable = [
        'titulo', 'descripcion', 'audio_url', 'duracion', 'invitados',
        'portada_path', 'fecha', 'orden', 'estado_publicacion', 'publicado_en',
        'user_create_id', 'user_update_id',
    ];

    protected $casts = [
        'fecha' => 'date',
        'publicado_en' => 'datetime',
    ];
}
