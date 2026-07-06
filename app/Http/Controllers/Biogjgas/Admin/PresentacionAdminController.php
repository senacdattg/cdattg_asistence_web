<?php

namespace App\Http\Controllers\Biogjgas\Admin;

use App\Http\Controllers\Controller;
use App\Services\Biogjgas\BiogjgasSubmoduleService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PresentacionAdminController extends Controller
{
    public function __construct(private readonly BiogjgasSubmoduleService $service)
    {
        $this->middleware('can:GESTIONAR PRESENTACION BIOGJGAS');
    }

    public function edit(): View
    {
        return view('biogjgas.admin.presentacion.edit', [
            'presentacion' => $this->service->presentacionAdmin(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'mision' => ['nullable', 'string'],
            'vision' => ['nullable', 'string'],
            'objetivo_general' => ['nullable', 'string'],
            'historia' => ['nullable', 'string'],
            'video_url' => ['nullable', 'string', 'max:500'],
            'politicas_pdf' => ['nullable', 'string', 'max:500'],
            'equipo_texto' => ['nullable', 'string'],
            'estado_publicacion' => ['required', Rule::in(['borrador', 'publicado', 'archivado'])],
        ]);

        $this->service->guardar('presentacion', $data, Auth::id(), $this->service->presentacionAdmin());

        return redirect()->route('biogjgas.admin.presentacion.edit')->with('success', 'Presentación actualizada correctamente.');
    }
}
