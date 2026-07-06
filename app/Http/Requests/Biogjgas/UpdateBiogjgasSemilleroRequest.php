<?php

namespace App\Http\Requests\Biogjgas;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBiogjgasSemilleroRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('GESTIONAR SEMILLERO BIOGJGAS') ?? false;
    }

    public function rules(): array
    {
        $semilleroId = $this->route('semillero')?->id ?? $this->route('semillero');

        return [
            'slug' => [
                'nullable', 'string', 'max:50', 'alpha_dash',
                Rule::unique('biogjgas_semilleros', 'slug')->ignore($semilleroId),
            ],
            'nombre' => ['required', 'string', 'max:200'],
            'sigla' => ['required', 'string', 'max:20'],
            'icono' => ['nullable', 'string', 'max:80'],
            'color_identidad' => ['nullable', 'string', 'max:20'],
            'resumen' => ['nullable', 'string', 'max:500'],
            'descripcion' => ['nullable', 'string'],
            'mision' => ['nullable', 'string'],
            'vision' => ['nullable', 'string'],
            'objetivos_texto' => ['nullable', 'string'],
            'instructor_lider' => ['nullable', 'string', 'max:200'],
            'correo_contacto' => ['nullable', 'email', 'max:150'],
            'orden' => ['nullable', 'integer', 'min:0'],
            'estado_publicacion' => ['required', Rule::in(['borrador', 'publicado', 'archivado'])],
        ];
    }
}
