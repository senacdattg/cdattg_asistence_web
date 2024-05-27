<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInstructorRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tipo_documento' => 'required',
            'numero_documento' => 'required|numeric',
            'primer_nombre' => 'required|alpha',
            'segundo_nombre' => 'nullable|alpha',
            'primer_apellido' => 'required|alpha',
            'segundo_apellido' => 'nullable|alpha',
            'fecha_de_nacimiento' => 'required|date',
            'genero' => 'required',
            'email' => 'required|email|unique:personas,email|unique:users,email',
            'regional_id' => 'required'
        ];
    }
}
