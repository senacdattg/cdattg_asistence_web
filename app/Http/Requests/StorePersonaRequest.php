<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePersonaRequest extends FormRequest
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
        return [
            'tipo_documento'      => 'required',
            'numero_documento'    => 'required|unique:personas,numero_documento',
            'primer_nombre'       => 'required|string',
            'segundo_nombre'      => 'nullable|string',
            'primer_apellido'     => 'required|string',
            'segundo_apellido'    => 'nullable|string',
            'fecha_nacimiento'    => 'required|date',
            'genero'              => 'required',
            'email'               => 'required|email|unique:personas,email|unique:users,email',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'tipo_documento.required'      => 'El tipo de documento es obligatorio.',
            'numero_documento.required'    => 'El número de documento es obligatorio.',
            'numero_documento.unique'      => 'El número de documento ya está registrado.',
            'primer_nombre.required'       => 'El primer nombre es obligatorio.',
            'primer_nombre.string'         => 'El primer nombre debe ser una cadena de texto.',
            'primer_apellido.required'     => 'El primer apellido es obligatorio.',
            'primer_apellido.string'       => 'El primer apellido debe ser una cadena de texto.',
            'fecha_de_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
            'fecha_de_nacimiento.date'     => 'La fecha de nacimiento debe ser una fecha válida.',
            'genero.required'              => 'El género es obligatorio.',
            'email.required'               => 'El correo electrónico es obligatorio.',
            'email.email'                  => 'El correo electrónico debe ser válido.',
            'email.unique'                 => 'El correo electrónico ya está en uso.',
        ];
    }
}
