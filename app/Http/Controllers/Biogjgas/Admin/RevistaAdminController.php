<?php

namespace App\Http\Controllers\Biogjgas\Admin;

use App\Services\Biogjgas\BiogjgasSubmoduleService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class RevistaAdminController extends BiogjgasSubmoduleAdminController
{
    public function __construct(BiogjgasSubmoduleService $service)
    {
        parent::__construct($service);
        $this->middleware('can:GESTIONAR REVISTA BIOGJGAS');
    }

    protected function modulo(): string { return 'revista'; }
    protected function permiso(): string { return 'GESTIONAR REVISTA BIOGJGAS'; }
    protected function titulo(): string { return 'Edición de revista'; }
    protected function icono(): string { return 'fa-book-open'; }
    protected function formView(): string { return 'biogjgas.admin.forms.revista'; }

    protected function validar(Request $request, ?Model $registro = null): array
    {
        return $request->validate(array_merge([
            'slug' => ['nullable', 'string', 'max:80', 'alpha_dash'],
            'titulo' => ['required', 'string', 'max:200'],
            'volumen' => ['nullable', 'integer', 'min:1'],
            'numero' => ['nullable', 'integer', 'min:1'],
            'anio' => ['required', 'integer', 'min:2000', 'max:2100'],
            'editorial' => ['nullable', 'string'],
            'issn' => ['nullable', 'string', 'max:30'],
            'articulos_texto' => ['nullable', 'string'],
            'fecha_publicacion' => ['nullable', 'date'],
            'orden' => ['nullable', 'integer', 'min:0'],
        ], $this->reglasPublicacion()));
    }
}
