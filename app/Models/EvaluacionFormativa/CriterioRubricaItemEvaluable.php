<?php

namespace App\Models\EvaluacionFormativa;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Interfaces\Auditable;
use App\Traits\Seguimiento;

class CriterioRubricaItemEvaluable extends Model implements Auditable
{
    use HasFactory, SoftDeletes, Seguimiento;

    protected $table = 'criterios_rubricas_item_evaluable';

    protected $fillable = [
        'item_evaluable_id',
        'rubricas_criterios_id',
        'peso_porcentual',
    ];

    protected $casts = [
        'peso_porcentual' => 'float',
    ];
}
