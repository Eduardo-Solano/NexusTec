<?php

namespace App\Http\Requests\Award;

use App\Models\Award;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAwardRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'team_id' => 'required|exists:teams,id',
            'position' => ['required', 'string', Rule::in(Award::POSITIONS)],
        ];
    }

    public function messages(): array
    {
        return [
            'team_id.required' => 'Debes seleccionar un equipo.',
            'team_id.exists' => 'El equipo seleccionado no existe.',
            'position.required' => 'La posición del premio es obligatoria.',
            'position.in' => 'La posición seleccionada no es válida.',
        ];
    }
}
