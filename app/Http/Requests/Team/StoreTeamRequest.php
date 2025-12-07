<?php

namespace App\Http\Requests\Team;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeamRequest extends FormRequest
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
            'name' => 'required|string|max:50',
            'event_id' => 'required|exists:events,id',
            'leader_role' => 'required|string',
            'members' => 'array|max:4',
            'member_roles' => 'array',
            'members.*' => 'nullable|email|distinct',
            'advisor_id' => 'required|exists:users,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del equipo es obligatorio.',
            'name.max' => 'El nombre del equipo no puede exceder 50 caracteres.',
            'event_id.required' => 'Debes seleccionar un evento.',
            'event_id.exists' => 'El evento seleccionado no existe.',
            'leader_role.required' => 'Debes especificar tu rol en el equipo.',
            'members.max' => 'Solo puedes invitar hasta 4 miembros adicionales.',
            'members.*.email' => 'El correo del miembro debe ser válido.',
            'members.*.distinct' => 'No puedes invitar al mismo correo dos veces.',
            'advisor_id.required' => 'Debes seleccionar un asesor.',
            'advisor_id.exists' => 'El asesor seleccionado no existe.',
        ];
    }
}
