<?php

namespace App\Models\EvaluacionFormativa;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Interfaces\Auditable;
use App\Traits\Seguimiento;

class Evaluacion extends Model implements Auditable
{
    use HasFactory, SoftDeletes, Seguimiento;

    protected $table = 'evaluaciones';

    protected $primaryKey = 'item_id';

    protected $fillable = [
        'tipo_evaluacion',
    ];

    protected $casts = [];
}
