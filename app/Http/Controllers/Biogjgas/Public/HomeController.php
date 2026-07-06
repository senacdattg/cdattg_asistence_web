<?php

namespace App\Http\Controllers\Biogjgas\Public;

use App\Http\Controllers\Controller;
use App\Services\Biogjgas\BiogjgasPublicContentService;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function __construct(
        private readonly BiogjgasPublicContentService $contentService
    ) {}

    public function index(): View
    {
        return view('biogjgas.public.index', [
            'banners' => $this->contentService->bannersActivos(),
            'semilleros' => $this->contentService->semillerosPublicados(),
        ]);
    }
}
