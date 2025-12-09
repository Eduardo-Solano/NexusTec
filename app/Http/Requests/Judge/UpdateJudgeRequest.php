<?php

namespace App\Http\Requests\Judge;

use Illuminate\Foundation\Http\FormRequest;

class UpdateJudgeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $judgeId = $this->route('judge')->id ?? $this->route('judge');
        
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $judgeId],
            'phone' => ['nullable', 'string', 'max:20'],
            'company' => ['nullable', 'string', 'max:255'],
            'specialty_id' => ['nullable', 'exists:specialties,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del juez es obligatorio.',
            'name.max' => 'El nombre no puede exceder 255 caracteres.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser válido.',
            'email.unique' => 'Este correo ya está registrado por otro usuario.',
            'phone.max' => 'El teléfono no puede exceder 20 caracteres.',
            'company.max' => 'La empresa no puede exceder 255 caracteres.',
            'specialty_id.exists' => 'La especialidad seleccionada no existe.',
        ];
    }
}
