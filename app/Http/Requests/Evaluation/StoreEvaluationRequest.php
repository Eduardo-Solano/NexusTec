<?php

namespace App\Http\Requests\Evaluation;

use Illuminate\Foundation\Http\FormRequest;

class StoreEvaluationRequest extends FormRequest
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
            'project_id' => 'required|exists:projects,id',
            'scores' => 'required|array',
            'scores.*' => 'required|integer|min:0',
            'feedback' => 'nullable|string',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'project_id.required' => 'El proyecto es obligatorio.',
            'project_id.exists' => 'El proyecto seleccionado no existe.',
            'scores.required' => 'Debes calificar todos los criterios.',
            'scores.array' => 'El formato de calificaciones es inválido.',
            'scores.*.required' => 'Cada criterio debe tener una calificación.',
            'scores.*.integer' => 'Las calificaciones deben ser números enteros.',
            'scores.*.min' => 'Las calificaciones no pueden ser negativas.',
        ];
    }
}
