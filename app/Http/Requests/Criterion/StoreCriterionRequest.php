<?php

namespace App\Http\Requests\Criterion;

use Illuminate\Foundation\Http\FormRequest;

class StoreCriterionRequest extends FormRequest
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
            'name' => 'required|string|max:255|unique:criteria,name',
            'max_points' => 'required|integer|min:1|max:100',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del criterio es obligatorio.',
            'name.max' => 'El nombre no puede exceder 255 caracteres.',
            'name.unique' => 'Ya existe un criterio con este nombre.',
            'max_points.required' => 'El puntaje máximo es obligatorio.',
            'max_points.integer' => 'El puntaje debe ser un número entero.',
            'max_points.min' => 'El puntaje mínimo es 1.',
            'max_points.max' => 'El puntaje máximo es 100.',
        ];
    }
}
