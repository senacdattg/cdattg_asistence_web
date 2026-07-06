<?php

namespace App\Models\Biogjgas;

use App\Models\Biogjgas\Concerns\Publicable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BiogjgasIntegrante extends Model
{
    use Publicable, SoftDeletes;

    protected $table = 'biogjgas_integrantes';

    protected $fillable = [
        'semillero_id', 'nombre', 'rol', 'programa', 'orden', 'estado_publicacion',
    ];

    public function semillero(): BelongsTo
    {
        return $this->belongsTo(BiogjgasSemillero::class, 'semillero_id');
    }
}
