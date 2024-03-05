<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FichaCaracterizacion extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'ficha_caracterizacion', 'ambiente_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
