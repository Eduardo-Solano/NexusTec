<?php

namespace App\Http\Requests\Team;

use Illuminate\Foundation\Http\FormRequest;

class JoinTeamRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'role' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'role.required' => 'Debes especificar tu rol en el equipo.',
        ];
    }
}
