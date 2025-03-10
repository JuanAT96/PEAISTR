<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SpecificCompetenceRequest extends FormRequest
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
            'graduate_profile' => 'required|string',
            'Learning_outcomes' => 'required|string',
            'contribution_level' => 'required|string',
            'subject_id' => 'required|exists:subjects,id',
        ];
    }

    public function messages(): array
    {
        return [
            'graduate_profile.required' => 'El perfil de egreso es obligatorio.',
            'Learning_outcomes.required' => 'El nombre de la unidad curricular es obligatorio.',
            'contribution_level.required' => 'El nombre de la unidad curricular es obligatorio.',
            'subject_id.required' => 'La asignatura es obligatoria.',
            'subject_id.exists' => 'La asignatura seleccionada no existe.',
        ];
    }
}
