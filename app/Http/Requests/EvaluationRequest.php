<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EvaluationRequest extends FormRequest
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
            'type' => 'required|string|max:255',
            'criteria' => 'required|string',
            'activity' => 'required|string',
            'first_partial' => 'nullable|numeric|between:0,10',
            'second_partial' => 'nullable|numeric|between:0,10',
            'subject_id' => 'required|exists:subjects,id',
            'unit' => 'required|integer|min:1',
            'Learning_outcomes' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => 'El tipo de evaluación es obligatorio.',
            'criteria.required' => 'Los criterios de evaluación son obligatorios.',
            'activity.required' => 'La actividad de evaluación es obligatoria.',
            'first_partial.between' => 'La nota del primer parcial debe estar entre 0 y 10.',
            'second_partial.between' => 'La nota del segundo parcial debe estar entre 0 y 10.',
            'subject_id.required' => 'La asignatura es obligatoria.',
            'subject_id.exists' => 'La asignatura seleccionada no existe.',
            'unit.required' => 'La unidad es obligatoria.',
            'unit.integer' => 'La unidad debe ser un número entero.',
            'unit.min' => 'La unidad debe ser al menos 1.',
            'Learning_outcomes.required' => 'Los resultados de aprendizaje son obligatorios.',
        ];
    }
}
