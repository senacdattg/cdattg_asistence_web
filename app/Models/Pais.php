<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pais extends Model
{
    use HasFactory;

    protected $fillable = [
        'pais',
        'status',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($pais) {
            $pais->pais = strtoupper($pais->pais);
        });
    }
}
