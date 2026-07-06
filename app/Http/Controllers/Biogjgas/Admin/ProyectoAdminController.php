<?php

namespace App\Http\Controllers\Biogjgas\Admin;

use App\Services\Biogjgas\BiogjgasSubmoduleService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProyectoAdminController extends BiogjgasSubmoduleAdminController
{
    public function __construct(BiogjgasSubmoduleService $service)
    {
        parent::__construct($service);
        $this->middleware('can:GESTIONAR SEMILLERO BIOGJGAS');
    }

    protected function modulo(): string { return 'proyecto'; }
    protected function permiso(): string { return 'GESTIONAR SEMILLERO BIOGJGAS'; }
    protected function titulo(): string { return 'Proyecto'; }
    protected function icono(): string { return 'fa-tasks'; }
    protected function formView(): string { return 'biogjgas.admin.forms.proyecto'; }

    protected function validar(Request $request, ?Model $registro = null): array
    {
        return $request->validate(array_merge([
            'semillero_id' => ['required', 'exists:biogjgas_semilleros,id'],
            'titulo' => ['required', 'string', 'max:200'],
            'descripcion' => ['nullable', 'string'],
            'estado_ejecucion' => ['required', Rule::in(['en_ejecucion', 'finalizado'])],
            'fecha_inicio' => ['nullable', 'date'],
            'fecha_fin' => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
            'orden' => ['nullable', 'integer', 'min:0'],
        ], $this->reglasPublicacion()));
    }
}
