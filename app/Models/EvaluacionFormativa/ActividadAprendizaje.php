<?php

namespace App\Models\EvaluacionFormativa;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Interfaces\Auditable;
use App\Traits\Seguimiento;

class ActividadAprendizaje extends Model implements Auditable
{
    use HasFactory, SoftDeletes, Seguimiento;

    protected $table = 'actividades_aprendizaje';

    protected $primaryKey = 'item_id';

    protected $fillable = [
        'tipo_actividad',
    ];

    protected $casts = [];
}
