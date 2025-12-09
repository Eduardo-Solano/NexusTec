<?php

namespace App\Http\Requests\Criterion;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCriterionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $criterionId = $this->route('criterion')->id ?? $this->route('criterion');
        
        return [
            'name' => 'required|string|max:255|unique:criteria,name,' . $criterionId,
            'max_points' => 'required|integer|min:1|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del criterio es obligatorio.',
            'name.max' => 'El nombre no puede exceder 255 caracteres.',
            'name.unique' => 'Ya existe otro criterio con este nombre.',
            'max_points.required' => 'El puntaje máximo es obligatorio.',
            'max_points.integer' => 'El puntaje debe ser un número entero.',
            'max_points.min' => 'El puntaje mínimo es 1.',
            'max_points.max' => 'El puntaje máximo es 100.',
        ];
    }
}
