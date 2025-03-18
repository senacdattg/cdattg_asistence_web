<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Regional extends Model
{
    use HasFactory;

    protected $fillable = ([
        'regional',
        'user_create_id',
        'user_edit_id',
        'status'
    ]);

    public function sedes()
    {
        return $this->hasMany(Sede::class);
    }

    public function userCreated()
    {
        return $this->belongsTo(User::class, 'user_create_id');
    }

    public function userEdited()
    {
        return $this->belongsTo(User::class, 'user_edit_id');
    }

    public function fichasCaracterizacion()
    {
        return $this->hasMany(FichaCaracterizacion::class, 'regional_id');
    }
}
