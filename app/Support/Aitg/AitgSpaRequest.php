<?php

namespace App\Support\Aitg;

use Illuminate\Http\Request;

/** Detecta peticiones de navegación SPA del módulo AITG. */
class AitgSpaRequest
{
    public const HEADER = 'X-AITG-SPA';

    public static function isFragment(?Request $request = null): bool
    {
        $request ??= request();

        return $request->headers->get(self::HEADER) === '1'
            || $request->boolean('aitg_spa');
    }
}
