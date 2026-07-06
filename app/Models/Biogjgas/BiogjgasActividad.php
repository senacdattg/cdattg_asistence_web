<?php

namespace App\Models\Biogjgas;

use App\Models\Biogjgas\Concerns\Publicable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BiogjgasActividad extends Model
{
    use Publicable, SoftDeletes;

    protected $table = 'biogjgas_actividades';

    protected $fillable = [
        'titulo', 'tipo', 'fecha', 'lugar', 'modalidad', 'descripcion',
        'semillero_id', 'estado_actividad', 'orden', 'estado_publicacion',
        'publicado_en', 'user_create_id', 'user_update_id',
    ];

    protected $casts = [
        'fecha' => 'date',
        'publicado_en' => 'datetime',
    ];

    public function semillero(): BelongsTo
    {
        return $this->belongsTo(BiogjgasSemillero::class, 'semillero_id');
    }
}
