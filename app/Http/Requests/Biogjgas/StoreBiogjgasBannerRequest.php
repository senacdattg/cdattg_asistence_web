<?php

namespace App\Http\Requests\Biogjgas;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBiogjgasBannerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('GESTIONAR BANNER BIOGJGAS') ?? false;
    }

    public function rules(): array
    {
        return [
            'titulo' => ['required', 'string', 'max:200'],
            'subtitulo' => ['nullable', 'string', 'max:300'],
            'enlace' => ['nullable', 'string', 'max:500'],
            'orden' => ['nullable', 'integer', 'min:0'],
            'vigente_desde' => ['nullable', 'date'],
            'vigente_hasta' => ['nullable', 'date', 'after_or_equal:vigente_desde'],
            'estado_publicacion' => ['required', Rule::in(['borrador', 'publicado', 'archivado'])],
        ];
    }
}
