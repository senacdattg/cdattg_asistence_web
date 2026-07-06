<?php

namespace App\Models\Biogjgas;

use App\Models\Biogjgas\Concerns\Publicable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BiogjgasProyecto extends Model
{
    use Publicable, SoftDeletes;

    protected $table = 'biogjgas_proyectos';

    protected $fillable = [
        'semillero_id', 'titulo', 'descripcion', 'estado_ejecucion',
        'fecha_inicio', 'fecha_fin', 'orden', 'estado_publicacion',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];

    public function semillero(): BelongsTo
    {
        return $this->belongsTo(BiogjgasSemillero::class, 'semillero_id');
    }
}
