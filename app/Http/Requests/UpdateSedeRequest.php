<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSedeRequest extends FormRequest
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

        $sedeId = $this->route('sede')->id;
        return [
            'sede' => 'required|unique:sedes,sede,' . $sedeId,
            'direccion' => 'required',
            'status' => 'required',
            'municipio_id' => 'required',
            'regional_id' => 'required',
        ];
    }
}
