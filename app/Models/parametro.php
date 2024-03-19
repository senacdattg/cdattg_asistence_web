<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class parametro extends Model
{
    use HasFactory;

    // Campos que puedes llenar al crear o actualizar un registro

    protected $fillable = ['name', 'status', 'user_create_id', 'user_edit_id'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($parametro) {
            $parametro->status = $parametro->status ?? 'ACTIVO';
        });
        static::saving(function ($parametro) {
            $parametro->name = strtoupper($parametro->name);
        });
    }
    public function userCreate()
    {
        return  $this->belongsTo(User::class, 'user_create_id');
    }
    public function userUpdate()
    {
        return  $this->belongsTo(User::class, 'user_edit_id');
    }

    public function temas()
    {
        return $this->belongsToMany(Tema::class, 'parametros_temas')->withPivot('status');
    }
}
