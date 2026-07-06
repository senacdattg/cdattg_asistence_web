<?php

namespace App\Http\Controllers\Biogjgas\Public;

use App\Http\Controllers\Controller;
use App\Services\Biogjgas\BiogjgasSubmoduleService;
use Illuminate\Contracts\View\View;

class PresentacionController extends Controller
{
    public function __construct(private readonly BiogjgasSubmoduleService $service) {}

    public function show(): View
    {
        $presentacion = $this->service->presentacionPublica();

        abort_if($presentacion === null, 404);

        return view('biogjgas.public.presentacion.show', compact('presentacion'));
    }
}
