<?php

namespace App\Models\Biogjgas\Concerns;

trait Publicable
{
    public function scopePublicados($query)
    {
        return $query->where('estado_publicacion', 'publicado');
    }
}
