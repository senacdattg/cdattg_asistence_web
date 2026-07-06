<?php

namespace App\Http\Controllers\Biogjgas\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Biogjgas\StoreBiogjgasSemilleroRequest;
use App\Http\Requests\Biogjgas\UpdateBiogjgasSemilleroRequest;
use App\Models\Biogjgas\BiogjgasSemillero;
use App\Services\Biogjgas\BiogjgasAdminService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class SemilleroAdminController extends Controller
{
    public function __construct(
        private readonly BiogjgasAdminService $adminService
    ) {
        $this->middleware('can:GESTIONAR SEMILLERO BIOGJGAS');
    }

    public function index(): View
    {
        return view('biogjgas.admin.semilleros.index', [
            'semilleros' => $this->adminService->semillerosPaginados(),
        ]);
    }

    public function create(): View
    {
        return view('biogjgas.admin.semilleros.create');
    }

    public function store(StoreBiogjgasSemilleroRequest $request): RedirectResponse
    {
        $this->adminService->crearSemillero($request->validated(), Auth::id());

        return redirect()
            ->route('biogjgas.admin.semilleros.index')
            ->with('success', 'Semillero creado correctamente.');
    }

    public function edit(BiogjgasSemillero $semillero): View
    {
        return view('biogjgas.admin.semilleros.edit', compact('semillero'));
    }

    public function update(UpdateBiogjgasSemilleroRequest $request, BiogjgasSemillero $semillero): RedirectResponse
    {
        $this->adminService->actualizarSemillero($semillero, $request->validated(), Auth::id());

        return redirect()
            ->route('biogjgas.admin.semilleros.index')
            ->with('success', 'Semillero actualizado correctamente.');
    }

    public function destroy(BiogjgasSemillero $semillero): RedirectResponse
    {
        $semillero->delete();

        return redirect()
            ->route('biogjgas.admin.semilleros.index')
            ->with('success', 'Semillero eliminado correctamente.');
    }
}
