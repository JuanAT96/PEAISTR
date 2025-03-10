<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CurricularUnitRequest extends FormRequest
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
            'subject_id' => 'required|exists:subjects,id',
            'unit' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'learning_outcomes' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'subject_id.required' => 'La asignatura es obligatoria.',
            'subject_id.exists' => 'La asignatura seleccionada no existe.',
            'unit.required' => 'La unidad curricular es obligatorio.',
            'name.required' => 'El nombre de la unidad curricular es obligatorio.',
            'learning_outcomes.required' => 'El resultado de aprendizaje es obligatorio.',
        ];
    }
}
