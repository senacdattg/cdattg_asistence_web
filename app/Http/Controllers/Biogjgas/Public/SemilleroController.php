<?php

namespace App\Http\Controllers\Biogjgas\Public;

use App\Http\Controllers\Controller;
use App\Services\Biogjgas\BiogjgasPublicContentService;
use App\Services\Biogjgas\BiogjgasSubmoduleService;
use Illuminate\Contracts\View\View;

class SemilleroController extends Controller
{
    public function __construct(
        private readonly BiogjgasPublicContentService $contentService,
        private readonly BiogjgasSubmoduleService $submoduleService,
    ) {}

    public function index(): View
    {
        return view('biogjgas.public.semilleros.index', [
            'semilleros' => $this->contentService->semillerosPublicados(),
        ]);
    }

    public function show(string $semillero): View
    {
        return view('biogjgas.public.semilleros.show', [
            'semillero' => $this->submoduleService->semilleroConRelaciones($semillero),
        ]);
    }
}