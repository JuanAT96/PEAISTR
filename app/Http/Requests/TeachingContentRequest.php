<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeachingContentRequest extends FormRequest
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
            'curricular_units_id' => 'required|exists:curricular_units,id',
            'schedule' => 'nullable|string',
            'content' => 'required|string',
            'contact_teacher' => 'required|string',
            'contact_teacher_duration' => 'required|integer|min:0',
            'practical_teaching' => 'required|string',
            'practical_teaching_duration' => 'required|integer|min:0',
            'practical_self_employment' => 'required|string',
            'practical_self_employment_duration' => 'required|integer|min:0',
            'self_employment' => 'required|string',
            'self_employment_duration' => 'required|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'curricular_units_id.required' => 'La unidad curricular es obligatoria.',
            'curricular_units_id.exists' => 'La unidad curricular seleccionada no existe.',
            'content.required' => 'El contenido es obligatorio.',
            'contact_teacher.required' => 'El contacto docente es obligatorio.',
            'contact_teacher_duration.required' => 'La duración del contacto docente es obligatoria.',
            'contact_teacher_duration.integer' => 'La duración del contacto docente debe ser un número entero.',
            'contact_teacher_duration.min' => 'La duración del contacto docente no puede ser negativa.',
            'practical_teaching.required' => 'La enseñanza práctica es obligatoria.',
            'practical_teaching_duration.required' => 'La duración de la enseñanza práctica es obligatoria.',
            'practical_teaching_duration.integer' => 'La duración de la enseñanza práctica debe ser un número entero.',
            'practical_teaching_duration.min' => 'La duración de la enseñanza práctica no puede ser negativa.',
            'practical_self_employment.required' => 'El autoempleo práctico es obligatorio.',
            'practical_self_employment_duration.required' => 'La duración del autoempleo práctico es obligatoria.',
            'practical_self_employment_duration.integer' => 'La duración del autoempleo práctico debe ser un número entero.',
            'practical_self_employment_duration.min' => 'La duración del autoempleo práctico no puede ser negativa.',
            'self_employment.required' => 'El autoempleo es obligatorio.',
            'self_employment_duration.required' => 'La duración del autoempleo es obligatoria.',
            'self_employment_duration.integer' => 'La duración del autoempleo debe ser un número entero.',
            'self_employment_duration.min' => 'La duración del autoempleo no puede ser negativa.',
        ];
    }
}
