<?php

namespace App\Models\Biogjgas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BiogjgasBanner extends Model
{
    use SoftDeletes;

    protected $table = 'biogjgas_banners';

    protected $fillable = [
        'titulo',
        'subtitulo',
        'imagen_path',
        'enlace',
        'orden',
        'vigente_desde',
        'vigente_hasta',
        'estado_publicacion',
        'publicado_en',
        'user_create_id',
        'user_update_id',
    ];

    protected $casts = [
        'vigente_desde' => 'date',
        'vigente_hasta' => 'date',
        'publicado_en' => 'datetime',
    ];

    public function scopePublicados($query)
    {
        return $query
            ->where('estado_publicacion', 'publicado')
            ->where(function ($q) {
                $q->whereNull('vigente_desde')->orWhere('vigente_desde', '<=', now()->toDateString());
            })
            ->where(function ($q) {
                $q->whereNull('vigente_hasta')->orWhere('vigente_hasta', '>=', now()->toDateString());
            });
    }
}
