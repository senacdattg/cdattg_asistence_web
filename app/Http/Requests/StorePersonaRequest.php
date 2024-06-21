<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $personaId = $this->persona->id;
        $userId = $this->persona->user->id; // Suponiendo que $this->instructor es una instancia del modelo User y tiene una relación con Persona

        return [
            'tipo_documento' => 'required',
            'numero_documento' => 'required',
            'primer_nombre' => 'required',
            'segundo_nombre' => 'nullable',
            'primer_apellido' => 'required',
            'segundo_apellido' => 'nullable',
            'fecha_de_nacimiento' => 'required|date',
            'genero' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('personas', 'email')->ignore($personaId),
                Rule::unique('users', 'email')->ignore($userId),
            ],
        ];
    }
}
