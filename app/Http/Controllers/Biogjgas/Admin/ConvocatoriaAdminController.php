<?php

namespace App\Http\Controllers\Biogjgas\Admin;

use App\Services\Biogjgas\BiogjgasSubmoduleService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ConvocatoriaAdminController extends BiogjgasSubmoduleAdminController
{
    public function __construct(BiogjgasSubmoduleService $service)
    {
        parent::__construct($service);
        $this->middleware('can:GESTIONAR CONVOCATORIA BIOGJGAS');
    }

    protected function modulo(): string { return 'convocatoria'; }
    protected function permiso(): string { return 'GESTIONAR CONVOCATORIA BIOGJGAS'; }
    protected function titulo(): string { return 'Convocatoria'; }
    protected function icono(): string { return 'fa-bullhorn'; }
    protected function formView(): string { return 'biogjgas.admin.forms.convocatoria'; }

    protected function validar(Request $request, ?Model $registro = null): array
    {
        return $request->validate(array_merge([
            'titulo' => ['required', 'string', 'max:200'],
            'tipo' => ['nullable', 'string', 'max:80'],
            'descripcion' => ['nullable', 'string'],
            'requisitos' => ['nullable', 'string'],
            'fecha_apertura' => ['nullable', 'date'],
            'fecha_cierre' => ['nullable', 'date', 'after_or_equal:fecha_apertura'],
            'documento_path' => ['nullable', 'string', 'max:500'],
            'enlace_externo' => ['nullable', 'string', 'max:500'],
            'estado_convocatoria' => ['required', Rule::in(['abierta', 'cerrada', 'proximamente'])],
            'semillero_id' => ['nullable', 'exists:biogjgas_semilleros,id'],
            'orden' => ['nullable', 'integer', 'min:0'],
        ], $this->reglasPublicacion()));
    }
}
