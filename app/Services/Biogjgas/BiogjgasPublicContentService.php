<?php

namespace App\Services\Biogjgas;

use App\Models\Biogjgas\BiogjgasBanner;
use App\Models\Biogjgas\BiogjgasSemillero;
use Illuminate\Database\Eloquent\Collection;

class BiogjgasPublicContentService
{
    public function bannersActivos(): Collection
    {
        return BiogjgasBanner::query()
            ->publicados()
            ->orderBy('orden')
            ->orderByDesc('publicado_en')
            ->get();
    }

    public function semillerosPublicados(): Collection
    {
        return BiogjgasSemillero::query()
            ->publicados()
            ->orderBy('orden')
            ->orderBy('nombre')
            ->get();
    }

    public function semilleroPorSlug(string $slug): BiogjgasSemillero
    {
        return BiogjgasSemillero::query()
            ->publicados()
            ->where('slug', $slug)
            ->firstOrFail();
    }
}
