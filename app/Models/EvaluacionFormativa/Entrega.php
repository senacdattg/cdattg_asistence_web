<?php

namespace App\Models\EvaluacionFormativa;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Interfaces\Auditable;
use App\Traits\Seguimiento;

class Entrega extends Model implements Auditable
{
    use HasFactory, SoftDeletes, Seguimiento;

    protected $table = 'entregas';

    protected $fillable = [
        'item_id',
        'aprendiz_id',
        'juicio',
        'estado',
        'observacion_instructor',
    ];

    protected $casts = [
        'juicio' => 'boolean',
    ];
}
