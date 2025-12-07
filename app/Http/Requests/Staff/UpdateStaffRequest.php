<?php

namespace App\Http\Requests\Staff;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStaffRequest extends FormRequest
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
        $staffId = $this->route('staff')->id ?? $this->route('staff');
        
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $staffId],
            'employee_number' => ['required', 'string'],
            'department' => ['required', 'string'],
            'staff_type' => ['required', 'in:advisor,staff,both'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'name.max' => 'El nombre no puede exceder 255 caracteres.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser válido.',
            'email.unique' => 'Este correo ya está registrado por otro usuario.',
            'employee_number.required' => 'El número de empleado es obligatorio.',
            'department.required' => 'El departamento es obligatorio.',
            'staff_type.required' => 'El tipo de personal es obligatorio.',
            'staff_type.in' => 'El tipo de personal no es válido.',
        ];
    }
}
