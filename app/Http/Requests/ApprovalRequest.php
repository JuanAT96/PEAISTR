<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApprovalRequest extends FormRequest
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
            'teacher_id' => 'required|exists:users,id',
            'coordinator_id' => 'required|exists:users,id',
            'vice_rector_id' => 'required|exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'subject_id.required' => 'La asignatura es obligatoria.',
            'subject_id.exists' => 'La asignatura seleccionada no existe.',
            'teacher_id.required' => 'El docente es obligatorio.',
            'teacher_id.exists' => 'El docente seleccionado no existe.',
            'coordinator_id.required' => 'El coordinador es obligatorio.',
            'coordinator_id.exists' => 'El coordinador seleccionado no existe.',
            'vice_rector_id.required' => 'El vicerrector es obligatorio.',
            'vice_rector_id.exists' => 'El vicerrector seleccionado no existe.',
        ];
    }
}
