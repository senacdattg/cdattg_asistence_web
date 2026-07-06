<?php

namespace App\Http\Controllers\Biogjgas\Public;

use App\Http\Controllers\Controller;
use App\Services\Biogjgas\BiogjgasSubmoduleService;
use Illuminate\Contracts\View\View;

class BoletinController extends Controller
{
    public function __construct(private readonly BiogjgasSubmoduleService $service) {}

    public function index(): View
    {
        return view('biogjgas.public.boletines.index', [
            'boletines' => $this->service->boletinesPublicados(),
        ]);
    }

    public function show(int $boletin): View
    {
        return view('biogjgas.public.boletines.show', [
            'boletin' => $this->service->boletinPublicado($boletin),
        ]);
    }
}
