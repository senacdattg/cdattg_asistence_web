<?php

namespace App\Http\Controllers\Biogjgas\Admin;

use App\Services\Biogjgas\BiogjgasSubmoduleService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class LineaAdminController extends BiogjgasSubmoduleAdminController
{
    public function __construct(BiogjgasSubmoduleService $service)
    {
        parent::__construct($service);
        $this->middleware('can:GESTIONAR SEMILLERO BIOGJGAS');
    }

    protected function modulo(): string { return 'linea'; }
    protected function permiso(): string { return 'GESTIONAR SEMILLERO BIOGJGAS'; }
    protected function titulo(): string { return 'Línea de investigación'; }
    protected function icono(): string { return 'fa-project-diagram'; }
    protected function formView(): string { return 'biogjgas.admin.forms.linea'; }

    protected function validar(Request $request, ?Model $registro = null): array
    {
        return $request->validate(array_merge([
            'semillero_id' => ['required', 'exists:biogjgas_semilleros,id'],
            'nombre' => ['required', 'string', 'max:200'],
            'descripcion' => ['nullable', 'string'],
            'orden' => ['nullable', 'integer', 'min:0'],
        ], $this->reglasPublicacion()));
    }
}
