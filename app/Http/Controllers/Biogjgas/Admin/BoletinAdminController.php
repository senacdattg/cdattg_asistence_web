<?php

namespace App\Http\Controllers\Biogjgas\Admin;

use App\Services\Biogjgas\BiogjgasSubmoduleService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class BoletinAdminController extends BiogjgasSubmoduleAdminController
{
    public function __construct(BiogjgasSubmoduleService $service)
    {
        parent::__construct($service);
        $this->middleware('can:GESTIONAR BOLETIN BIOGJGAS');
    }

    protected function modulo(): string { return 'boletin'; }
    protected function permiso(): string { return 'GESTIONAR BOLETIN BIOGJGAS'; }
    protected function titulo(): string { return 'Boletín'; }
    protected function icono(): string { return 'fa-newspaper'; }
    protected function formView(): string { return 'biogjgas.admin.forms.boletin'; }

    protected function validar(Request $request, ?Model $registro = null): array
    {
        return $request->validate(array_merge([
            'titulo' => ['required', 'string', 'max:200'],
            'numero' => ['nullable', 'string', 'max:30'],
            'fecha' => ['nullable', 'date'],
            'resumen' => ['nullable', 'string'],
            'pdf_path' => ['nullable', 'string', 'max:500'],
            'portada_path' => ['nullable', 'string', 'max:500'],
            'tematica' => ['nullable', 'string', 'max:150'],
            'orden' => ['nullable', 'integer', 'min:0'],
        ], $this->reglasPublicacion()));
    }
}
