<?php

namespace App\Http\Controllers\Biogjgas\Admin;

use App\Services\Biogjgas\BiogjgasSubmoduleService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class PodcastAdminController extends BiogjgasSubmoduleAdminController
{
    public function __construct(BiogjgasSubmoduleService $service)
    {
        parent::__construct($service);
        $this->middleware('can:GESTIONAR PODCAST BIOGJGAS');
    }

    protected function modulo(): string { return 'podcast'; }
    protected function permiso(): string { return 'GESTIONAR PODCAST BIOGJGAS'; }
    protected function titulo(): string { return 'Episodio de podcast'; }
    protected function icono(): string { return 'fa-podcast'; }
    protected function formView(): string { return 'biogjgas.admin.forms.podcast'; }

    protected function validar(Request $request, ?Model $registro = null): array
    {
        return $request->validate(array_merge([
            'titulo' => ['required', 'string', 'max:200'],
            'descripcion' => ['nullable', 'string'],
            'audio_url' => ['nullable', 'string', 'max:500'],
            'duracion' => ['nullable', 'string', 'max:20'],
            'invitados' => ['nullable', 'string', 'max:300'],
            'portada_path' => ['nullable', 'string', 'max:500'],
            'fecha' => ['nullable', 'date'],
            'orden' => ['nullable', 'integer', 'min:0'],
        ], $this->reglasPublicacion()));
    }
}
