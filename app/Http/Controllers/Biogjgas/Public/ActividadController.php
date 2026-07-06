<?php

namespace App\Http\Controllers\Biogjgas\Public;

use App\Http\Controllers\Controller;
use App\Services\Biogjgas\BiogjgasSubmoduleService;
use Illuminate\Contracts\View\View;

class ActividadController extends Controller
{
    public function __construct(private readonly BiogjgasSubmoduleService $service) {}

    public function index(): View
    {
        return view('biogjgas.public.actividades.index', [
            'actividades' => $this->service->actividadesPublicadas(),
        ]);
    }

    public function show(int $actividad): View
    {
        return view('biogjgas.public.actividades.show', [
            'actividad' => $this->service->actividadPublicada($actividad),
        ]);
    }
}
