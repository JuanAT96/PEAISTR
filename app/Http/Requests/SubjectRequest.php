<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubjectRequest extends FormRequest
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
            'subject_name' => ['required', 'string', 'max:150', Rule::unique('subjects', 'subject_name')->ignore($this->route('subject'))],
            'subject_code' => ['required', 'string', 'max:50', Rule::unique('subjects', 'subject_code')->ignore($this->route('subject'))],
            'curricular_org_unit' => ['nullable', 'string', 'max:255'],
            'academic_period_id' => 'required|exists:academic_periods,id',
            'credits' => 'nullable|integer|min:1',
            'contact_teacher' => 'nullable|integer|min:0',
            'practical_teaching' => 'nullable|integer|min:0',
            'practical_self_employment' => 'nullable|integer|min:0',
            'self_employment' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'objectives' => 'nullable|string',
            'gral_learning_outcome' => 'nullable|string',
            'description_integrative_project' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'subject_name.required' => 'El nombre de la asignatura es obligatorio.',
            'subject_name.unique' => 'El nombre de la asignatura ya existe.',
            'subject_code.required' => 'El código de la asignatura es obligatorio.',
            'subject_code.unique' => 'El código de la asignatura ya existe.',
            'academic_period_id.required' => 'El período académico es obligatorio.',
            'academic_period_id.exists' => 'El período académico seleccionado no existe.',
            'credits.min' => 'Los créditos deben ser al menos 1.',
            'contact_teacher.min' => 'El contacto del profesor debe ser al menos 0.',
            'practical_teaching.min' => 'La enseñanza práctica debe ser al menos 0.',
            'practical_self_employment.min' => 'La práctica de autoempleo debe ser al menos 0.',
            'self_employment.min' => 'El autoempleo debe ser al menos 0.',
        ];
    }
}
