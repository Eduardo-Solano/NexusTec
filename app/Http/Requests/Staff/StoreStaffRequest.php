<?php

namespace App\Http\Requests\Staff;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class StoreStaffRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'employee_number' => ['required', 'string', 'unique:staff_profiles'],
            'department' => ['required', 'string'],
            'staff_type' => ['required', 'in:advisor,staff,both'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'name.max' => 'El nombre no puede exceder 255 caracteres.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser válido.',
            'email.unique' => 'Este correo ya está registrado en el sistema.',
            'employee_number.required' => 'El número de empleado es obligatorio.',
            'employee_number.unique' => 'Este número de empleado ya está registrado.',
            'department.required' => 'El departamento es obligatorio.',
            'staff_type.required' => 'El tipo de personal es obligatorio.',
            'staff_type.in' => 'El tipo de personal no es válido.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ];
    }
}
