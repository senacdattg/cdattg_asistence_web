<?php

namespace App\Models\EvaluacionFormativa;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Interfaces\Auditable;
use App\Traits\Seguimiento;

class EntregaCalificacionCriterio extends Model implements Auditable
{
    use HasFactory, SoftDeletes, Seguimiento;

    protected $table = 'entregas_calificaciones_criterios';

    protected $fillable = [
        'entrega_id',
        'rubrica_criterio_id',
        'juicio',
        'estado',
    ];

    protected $casts = [
        'juicio' => 'boolean',
        'estado' => 'boolean',
    ];
}
