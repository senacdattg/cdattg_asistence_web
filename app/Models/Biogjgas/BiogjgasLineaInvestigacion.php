<?php

namespace App\Models\Biogjgas;

use App\Models\Biogjgas\Concerns\Publicable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BiogjgasLineaInvestigacion extends Model
{
    use Publicable, SoftDeletes;

    protected $table = 'biogjgas_lineas_investigacion';

    protected $fillable = [
        'semillero_id', 'nombre', 'descripcion', 'orden', 'estado_publicacion',
    ];

    public function semillero(): BelongsTo
    {
        return $this->belongsTo(BiogjgasSemillero::class, 'semillero_id');
    }
}
