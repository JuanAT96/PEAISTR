<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CareerSubjectRequest extends FormRequest
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
            'career_id' => 'required|exists:careers,id',
            'subject_id' => 'required|exists:subjects,id',
            'level' => 'required|string',
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     */
    public function messages(): array
    {
        return [
            'career_id.required' => 'La carrera es obligatoria.',
            'career_id.exists' => 'La carrera seleccionada no existe.',
            'subject_id.required' => 'La asignatura es obligatoria.',
            'subject_id.exists' => 'La asignatura seleccionada no existe.',
            'level.required' => 'El nivel es obligatorio.',
        ];
    }
}
