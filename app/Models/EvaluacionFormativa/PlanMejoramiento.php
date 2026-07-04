<?php

namespace App\Models\EvaluacionFormativa;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Interfaces\Auditable;
use App\Traits\Seguimiento;

class PlanMejoramiento extends Model implements Auditable
{
    use HasFactory, SoftDeletes, Seguimiento;

    protected $table = 'planes_mejoramiento';

    protected $primaryKey = 'item_id';

    protected $fillable = [
        'aprendiz_id',
        'item_origen_id',
        'estado',
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];
}
