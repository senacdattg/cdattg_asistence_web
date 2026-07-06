<?php

namespace App\Http\Controllers\Biogjgas\Public;

use App\Http\Controllers\Controller;
use App\Services\Biogjgas\BiogjgasSubmoduleService;
use Illuminate\Contracts\View\View;

class ConvocatoriaController extends Controller
{
    public function __construct(private readonly BiogjgasSubmoduleService $service) {}

    public function index(): View
    {
        return view('biogjgas.public.convocatorias.index', [
            'convocatorias' => $this->service->convocatoriasPublicadas(),
        ]);
    }

    public function show(int $convocatoria): View
    {
        return view('biogjgas.public.convocatorias.show', [
            'convocatoria' => $this->service->convocatoriaPublicada($convocatoria),
        ]);
    }
}
