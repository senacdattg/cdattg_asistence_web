<?php

namespace App\Http\Requests;

use App\Models\Instructor;
use App\Models\Persona;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateInstructorRequest extends FormRequest
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
        $instructorID = $this->route('instructor')->id;

        $instructor = Instructor::find($instructorID);
        $persona = Persona::find($instructor->persona_id);
        $user = User::where('persona_id', $persona->id)->first();
        // @dd($user);
        return [
            'tipo_documento' => 'required',
            'numero_documento' => 'required|numeric',
            'primer_nombre' => 'required|alpha',
            'segundo_nombre' => 'nullable|alpha',
            'primer_apellido' => 'required|alpha',
            'segundo_apellido' => 'nullable|alpha',
            'fecha_de_nacimiento' => 'required|date',
            'genero' => 'required',
            // 'email' => 'required|email|unique:personas,email,' . $persona->id,
            // 'email' => 'required|email|unique:users,email,' . $user->id,
            'email' => [
                'required',
                'email',
                \Illuminate\Validation\Rule::unique('personas', 'email')->ignore($persona->id),
                \Illuminate\Validation\Rule::unique('users', 'email')->ignore($user->id),
            ],
            'regional_id' => 'required'
        ];
    }
}
