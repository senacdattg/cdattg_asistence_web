<?php

namespace App\Http\Controllers\Biogjgas\Public;

use App\Http\Controllers\Controller;
use App\Services\Biogjgas\BiogjgasSubmoduleService;
use Illuminate\Contracts\View\View;

class PodcastController extends Controller
{
    public function __construct(private readonly BiogjgasSubmoduleService $service) {}

    public function index(): View
    {
        return view('biogjgas.public.podcast.index', [
            'episodios' => $this->service->podcastsPublicados(),
        ]);
    }

    public function show(int $podcast): View
    {
        return view('biogjgas.public.podcast.show', [
            'episodio' => $this->service->podcastPublicado($podcast),
        ]);
    }
}
