<?php

namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'start_date' => 'required|date',
            'registration_deadline' => 'required|date|after_or_equal:start_date',
            'end_date' => 'required|date|after:registration_deadline',
            'criteria' => 'required|array|min:1',
            'criteria.*' => 'exists:criteria,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del evento es obligatorio.',
            'name.max' => 'El nombre no puede exceder 255 caracteres.',
            'description.max' => 'La descripción no puede exceder 1000 caracteres.',
            'start_date.required' => 'La fecha de inicio es obligatoria.',
            'start_date.date' => 'La fecha de inicio debe ser una fecha válida.',
            'registration_deadline.required' => 'La fecha límite de inscripciones es obligatoria.',
            'registration_deadline.after_or_equal' => 'La fecha límite debe ser igual o posterior a la fecha de inicio.',
            'end_date.required' => 'La fecha de cierre es obligatoria.',
            'end_date.after' => 'La fecha de cierre debe ser posterior a la fecha límite de inscripciones.',
            'criteria.required' => 'Debes seleccionar al menos un criterio de evaluación.',
            'criteria.min' => 'Debes seleccionar al menos un criterio de evaluación.',
            'criteria.*.exists' => 'Uno de los criterios seleccionados no existe.',
        ];
    }
}
