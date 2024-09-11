<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramaCaraterizacion extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'ficha_id',
        'instructor_id',
        'sede_id'
    ];

    public function ficha()
    {
        return $this->belongsTo(FichaCaracterizacion::class, 'ficha_id');
    }

    public function instructor()
    {
        return $this->belongsTo(Instructor::class, 'instructor_id');
    }

    public function fichas()
    {
        return $this->hasMany(FichaCaracterizacion::class, 'instructor_id');
    }

    public function sede()
    {
        return $this->belongsTo(Sede::class, 'sede_id');
    }
}
