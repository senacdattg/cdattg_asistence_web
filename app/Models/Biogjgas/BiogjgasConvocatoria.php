<?php

namespace App\Models\Biogjgas;

use App\Models\Biogjgas\Concerns\Publicable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BiogjgasConvocatoria extends Model
{
    use Publicable, SoftDeletes;

    protected $table = 'biogjgas_convocatorias';

    protected $fillable = [
        'titulo', 'tipo', 'descripcion', 'requisitos', 'fecha_apertura',
        'fecha_cierre', 'documento_path', 'enlace_externo', 'estado_convocatoria',
        'semillero_id', 'orden', 'estado_publicacion', 'publicado_en',
        'user_create_id', 'user_update_id',
    ];

    protected $casts = [
        'fecha_apertura' => 'date',
        'fecha_cierre' => 'date',
        'publicado_en' => 'datetime',
    ];

    public function semillero(): BelongsTo
    {
        return $this->belongsTo(BiogjgasSemillero::class, 'semillero_id');
    }

    public function scopeVigentes($query)
    {
        return $query->publicados()
            ->whereIn('estado_convocatoria', ['abierta', 'proximamente']);
    }
}
