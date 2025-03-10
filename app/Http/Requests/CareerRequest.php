<?php

namespace App\Http\Requests;

use GuzzleHttp\Psr7\Message;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CareerRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:100', Rule::unique('careers', 'name')->ignore($this->route('career'))],
            'research_lines' => 'nullable|string',
            'coordinator' => 'nullable|string',
            'modality' => 'nullable|string',
            'duration' => 'required|integer', // Asegúrate de que esta regla esté aquí
            //'logo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre de la carrera es obligatorio.',
            'name.unique' => 'La carrera ya existe.',
            'duration.required' => 'La duración es obligatoria.',
            'duration.min' => 'La duración debe ser de al menos 1 mes.',
        ];
    }
}
