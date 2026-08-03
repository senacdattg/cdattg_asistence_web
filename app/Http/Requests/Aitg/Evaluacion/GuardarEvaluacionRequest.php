<?php

namespace App\Http\Requests\Aitg\Evaluacion;

use Illuminate\Foundation\Http\FormRequest;

class GuardarEvaluacionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('EVALUAR POSTULACION AITG') ?? false;
    }

    public function rules(): array
    {
        return [
            'observaciones' => ['nullable', 'string', 'max:2000'],
            'resultado' => ['nullable', 'in:aprobado,rechazado'],
            'checklist' => ['nullable', 'array'],
            'checklist.*.cumple' => ['nullable', 'in:0,1,true,false'],
            'checklist.*.observaciones' => ['nullable', 'string', 'max:1000'],
            'puntos' => ['nullable', 'array'],
            'puntos.*.cumple' => ['nullable', 'in:0,1,true,false'],
            'puntos.*.observaciones' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
