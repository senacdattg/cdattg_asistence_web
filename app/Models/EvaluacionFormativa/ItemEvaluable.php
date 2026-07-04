<?php

namespace App\Models\EvaluacionFormativa;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Interfaces\Auditable;
use App\Traits\Seguimiento;

class ItemEvaluable extends Model implements Auditable
{
    use HasFactory, SoftDeletes, Seguimiento;

    protected $table = 'item_evaluable';

    protected $primaryKey = 'item_id';

    protected $fillable = [
        'titulo',
        'descripcion',
        'fecha_limite_entrega',
        'estado',
        'tipo_actividad',
    ];

    protected $casts = [
        'fecha_limite_entrega' => 'datetime',
    ];
}
