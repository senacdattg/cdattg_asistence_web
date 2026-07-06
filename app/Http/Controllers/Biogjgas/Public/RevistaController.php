<?php

namespace App\Http\Controllers\Biogjgas\Public;

use App\Http\Controllers\Controller;
use App\Services\Biogjgas\BiogjgasSubmoduleService;
use Illuminate\Contracts\View\View;

class RevistaController extends Controller
{
    public function __construct(private readonly BiogjgasSubmoduleService $service) {}

    public function index(): View
    {
        return view('biogjgas.public.revista.index', [
            'ediciones' => $this->service->revistaPublicada(),
        ]);
    }

    public function show(string $edicion): View
    {
        return view('biogjgas.public.revista.show', [
            'edicion' => $this->service->revistaEdicionPorSlug($edicion),
        ]);
    }
}
