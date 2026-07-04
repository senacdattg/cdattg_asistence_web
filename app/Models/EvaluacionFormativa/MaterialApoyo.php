<?php

namespace App\Models\EvaluacionFormativa;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Interfaces\Auditable;
use App\Traits\Seguimiento;

class MaterialApoyo extends Model implements Auditable
{
    use HasFactory, SoftDeletes, Seguimiento;

    protected $table = 'materiales_apoyo';

    protected $fillable = [
        'titulo',
        'descripcion',
        'tipo_material_id',
        'archivo_ruta',
        'archivo_url',
        'mime_type',
        'extension',
        'tamano_bytes',
        'estado',
    ];

    protected $casts = [
        'estado' => 'boolean',
        'tamano_bytes' => 'integer',
    ];
}
