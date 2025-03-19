<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTemaRequest extends FormRequest
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
        // Obtenemos el ID del tema actual desde la ruta.
        $temaId = $this->route('tema')->id;

        return [
            'name'   => 'required|string|max:255|unique:temas,name,' . $temaId,
            'status' => 'required|boolean',
        ];
    }
}
