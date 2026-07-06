<?php

namespace App\Http\Controllers\Biogjgas\Admin;

use App\Http\Controllers\Controller;
use App\Services\Biogjgas\BiogjgasSubmoduleService;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

abstract class BiogjgasSubmoduleAdminController extends Controller
{
    public function __construct(
        protected readonly BiogjgasSubmoduleService $service
    ) {}

    abstract protected function modulo(): string;

    abstract protected function permiso(): string;

    abstract protected function titulo(): string;

    abstract protected function icono(): string;

    abstract protected function formView(): string;

    protected function rutaBase(): string
    {
        return 'biogjgas.admin.'.$this->modulo();
    }

    protected function vistaBase(): string
    {
        return 'biogjgas.admin.submodulo';
    }

    protected function datosFormulario(): array
    {
        return [
            'tituloModulo' => $this->titulo(),
            'icono' => $this->icono(),
            'formView' => $this->formView(),
            'rutaBase' => $this->rutaBase(),
            'semilleros' => $this->service->semillerosParaSelect(),
        ];
    }

    public function index(): View
    {
        $this->authorize($this->permiso());

        return view($this->vistaBase().'.index', array_merge($this->datosFormulario(), [
            'registros' => $this->service->listarOrdenado($this->modulo()),
        ]));
    }

    public function create(): View
    {
        $this->authorize($this->permiso());

        return view($this->vistaBase().'.create', $this->datosFormulario());
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize($this->permiso());
        $this->service->guardar($this->modulo(), $this->validar($request), Auth::id());

        return redirect()->route($this->rutaBase().'.index')->with('success', $this->titulo().' creado correctamente.');
    }

    public function edit(int $registro): View
    {
        $this->authorize($this->permiso());

        return view($this->vistaBase().'.edit', array_merge($this->datosFormulario(), [
            'registro' => $this->service->findOrFail($this->modulo(), $registro),
        ]));
    }

    public function update(Request $request, int $registro): RedirectResponse
    {
        $this->authorize($this->permiso());
        $modelo = $this->service->findOrFail($this->modulo(), $registro);
        $this->service->guardar($this->modulo(), $this->validar($request, $modelo), Auth::id(), $modelo);

        return redirect()->route($this->rutaBase().'.index')->with('success', $this->titulo().' actualizado correctamente.');
    }

    public function destroy(int $registro): RedirectResponse
    {
        $this->authorize($this->permiso());
        $this->service->eliminar($this->modulo(), $this->service->findOrFail($this->modulo(), $registro));

        return redirect()->route($this->rutaBase().'.index')->with('success', $this->titulo().' eliminado correctamente.');
    }

    protected function reglasPublicacion(): array
    {
        return ['estado_publicacion' => ['required', Rule::in(['borrador', 'publicado', 'archivado'])]];
    }

    abstract protected function validar(Request $request, ?Model $registro = null): array;
}
