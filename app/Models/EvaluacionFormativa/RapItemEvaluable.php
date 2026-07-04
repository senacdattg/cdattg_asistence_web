<?php

namespace App\Models\EvaluacionFormativa;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Interfaces\Auditable;
use App\Traits\Seguimiento;

class RapItemEvaluable extends Model implements Auditable
{
    use HasFactory, SoftDeletes, Seguimiento;

    protected $table = 'rap_item_evaluable';

    protected $fillable = [
        'item_evaluable_id',
        'rap_id',
        'estado',
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];
}
