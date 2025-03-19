<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Regional extends Model
{
    use HasFactory;

    /**
     * Atributos asignables.
     *
     * @var array
     */
    protected $fillable = [
        'regional',
        'user_create_id',
        'user_edit_id',
        'status',
    ];

    /**
     * Boot del modelo para eventos.
     */
    protected static function boot()
    {
        parent::boot();

        // Convertir el nombre de la regional a mayúsculas antes de guardar.
        static::saving(function ($regional) {
            $regional->regional = strtoupper($regional->regional);
        });
    }

    /**
     * Relación: Una regional tiene muchas sedes.
     */
    public function sedes()
    {
        return $this->hasMany(Sede::class);
    }

    /**
     * Relación: Regional creada por un usuario.
     */
    public function userCreated()
    {
        return $this->belongsTo(User::class, 'user_create_id');
    }

    /**
     * Relación: Regional modificada por un usuario.
     */
    public function userEdited()
    {
        return $this->belongsTo(User::class, 'user_edit_id');
    }

    /**
     * Relación: Una regional tiene muchas fichas de caracterización.
     */
    public function fichasCaracterizacion()
    {
        return $this->hasMany(FichaCaracterizacion::class, 'regional_id');
    }
}
