<?php

namespace App\Http\Controllers\Biogjgas\Admin;

use App\Services\Biogjgas\BiogjgasSubmoduleService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class IntegranteAdminController extends BiogjgasSubmoduleAdminController
{
    public function __construct(BiogjgasSubmoduleService $service)
    {
        parent::__construct($service);
        $this->middleware('can:GESTIONAR SEMILLERO BIOGJGAS');
    }

    protected function modulo(): string { return 'integrante'; }
    protected function permiso(): string { return 'GESTIONAR SEMILLERO BIOGJGAS'; }
    protected function titulo(): string { return 'Integrante'; }
    protected function icono(): string { return 'fa-users'; }
    protected function formView(): string { return 'biogjgas.admin.forms.integrante'; }

    protected function validar(Request $request, ?Model $registro = null): array
    {
        return $request->validate(array_merge([
            'semillero_id' => ['required', 'exists:biogjgas_semilleros,id'],
            'nombre' => ['required', 'string', 'max:200'],
            'rol' => ['nullable', 'string', 'max:100'],
            'programa' => ['nullable', 'string', 'max:150'],
            'orden' => ['nullable', 'integer', 'min:0'],
        ], $this->reglasPublicacion()));
    }
}
