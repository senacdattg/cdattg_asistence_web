<?php

namespace App\Services\Biogjgas;

use Illuminate\Database\Eloquent\Model;

class BiogjgasPublicacionHelper
{
    public static function preparar(array $data, ?int $userId, ?Model $existente = null): array
    {
        $payload = $data;

        if (isset($payload['estado_publicacion'])
            && $payload['estado_publicacion'] === 'publicado'
            && ($existente?->estado_publicacion !== 'publicado')) {
            $payload['publicado_en'] = now();
        }

        if ($userId) {
            $payload[$existente ? 'user_update_id' : 'user_create_id'] = $userId;
        }

        return $payload;
    }
}
