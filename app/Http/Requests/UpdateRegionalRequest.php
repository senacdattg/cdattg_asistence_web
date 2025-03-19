<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRegionalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $regionalId = $this->route('regional')->id;

        return [
            'regional' => 'required|string|unique:regionals,regional,' . $regionalId,
            'status'   => 'required|boolean',
        ];
    }

    /**
     * Get custom error messages for validation.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'regional.required' => 'El nombre de la regional es obligatorio.',
            'regional.string'   => 'El nombre de la regional debe ser una cadena de caracteres.',
            'regional.unique'   => 'El nombre de la regional ya existe.',
            'status.required'   => 'El estado es obligatorio.',
            'status.boolean'    => 'El estado debe ser verdadero o falso.',
        ];
    }
}
