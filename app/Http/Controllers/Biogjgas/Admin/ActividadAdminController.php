<?php

namespace App\Http\Controllers\Biogjgas\Admin;

use App\Services\Biogjgas\BiogjgasSubmoduleService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ActividadAdminController extends BiogjgasSubmoduleAdminController
{
    public function __construct(BiogjgasSubmoduleService $service)
    {
        parent::__construct($service);
        $this->middleware('can:GESTIONAR ACTIVIDAD BIOGJGAS');
    }

    protected function modulo(): string { return 'actividad'; }
    protected function permiso(): string { return 'GESTIONAR ACTIVIDAD BIOGJGAS'; }
    protected function titulo(): string { return 'Actividad investigativa'; }
    protected function icono(): string { return 'fa-calendar-alt'; }
    protected function formView(): string { return 'biogjgas.admin.forms.actividad'; }

    protected function validar(Request $request, ?Model $registro = null): array
    {
        return $request->validate(array_merge([
            'titulo' => ['required', 'string', 'max:200'],
            'tipo' => ['nullable', 'string', 'max:80'],
            'fecha' => ['nullable', 'date'],
            'lugar' => ['nullable', 'string', 'max:200'],
            'modalidad' => ['nullable', 'string', 'max:50'],
            'descripcion' => ['nullable', 'string'],
            'semillero_id' => ['nullable', 'exists:biogjgas_semilleros,id'],
            'estado_actividad' => ['required', Rule::in(['programada', 'realizada', 'cancelada'])],
            'orden' => ['nullable', 'integer', 'min:0'],
        ], $this->reglasPublicacion()));
    }
}
