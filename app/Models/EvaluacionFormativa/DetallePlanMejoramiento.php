<?php

namespace App\Models\EvaluacionFormativa;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Interfaces\Auditable;
use App\Traits\Seguimiento;

class DetallePlanMejoramiento extends Model implements Auditable
{
    use HasFactory, SoftDeletes, Seguimiento;

    protected $table = 'detalles_plan_mejoramiento';

    protected $fillable = [
        'plan_mejoramiento_id',
        'criterios_deficientes',
    ];

    protected $casts = [];
}
