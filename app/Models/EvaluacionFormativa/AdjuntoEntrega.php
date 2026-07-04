<?php

namespace App\Models\EvaluacionFormativa;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Interfaces\Auditable;
use App\Traits\Seguimiento;

class AdjuntoEntrega extends Model implements Auditable
{
    use HasFactory, SoftDeletes, Seguimiento;

    protected $table = 'adjuntos_entrega';

    protected $fillable = [
        'entrega_id',
        'nombre_original',
        'archivo_ruta',
        'mime_type',
        'tamano_bytes',
        'extension',
        'estado',
    ];

    protected $casts = [
        'estado' => 'boolean',
        'tamano_bytes' => 'integer',
    ];
}
