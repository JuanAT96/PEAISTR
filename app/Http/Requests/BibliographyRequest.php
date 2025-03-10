<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BibliographyRequest extends FormRequest
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
            'author' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:' . date('Y'),
            'title' => 'required|string|max:255',
            'editorial' => 'required|string|max:255',
            'format' => 'required|string|max:255',
            'code' => 'nullable|string|max:255',
            'link' => 'nullable|url',
            'subject_id' => 'required|exists:subjects,id',
            'uplift' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => 'El tipo de bibliografía es obligatorio.',
            'author.required' => 'El autor es obligatorio.',
            'year.required' => 'El año es obligatorio.',
            'year.min' => 'El año debe ser mayor o igual a 1900.',
            'year.max' => 'El año no puede ser mayor al año actual.',
            'title.required' => 'El título es obligatorio.',
            'editorial.required' => 'La editorial es obligatoria.',
            'format.required' => 'El formato es obligatorio.',
            'link.url' => 'El enlace debe ser una URL válida.',
            'subject_id.required' => 'La asignatura es obligatoria.',
            'subject_id.exists' => 'La asignatura seleccionada no existe.',
            'uplift.required' => 'El levantamiento es obligatorio.',
        ];
    }
}
