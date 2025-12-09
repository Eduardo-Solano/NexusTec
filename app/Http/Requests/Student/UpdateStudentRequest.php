<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $student = $this->route('student');
        $studentId = is_object($student) ? $student->id : $student;
        $profileId = is_object($student) && $student->studentProfile 
            ? $student->studentProfile->id 
            : null;
        
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $studentId,
            'control_number' => 'required|unique:student_profiles,control_number,' . $profileId,
            'career_id' => 'required|exists:careers,id',
            'semester' => 'required|integer|min:1|max:12',
            'phone' => 'nullable|string|max:20',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del estudiante es obligatorio.',
            'name.max' => 'El nombre no puede exceder 255 caracteres.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser válido.',
            'email.unique' => 'Este correo ya está registrado por otro usuario.',
            'control_number.required' => 'El número de control es obligatorio.',
            'control_number.unique' => 'Este número de control ya está registrado.',
            'career_id.required' => 'La carrera es obligatoria.',
            'career_id.exists' => 'La carrera seleccionada no existe.',
            'semester.required' => 'El semestre es obligatorio.',
            'semester.integer' => 'El semestre debe ser un número.',
            'semester.min' => 'El semestre mínimo es 1.',
            'semester.max' => 'El semestre máximo es 12.',
            'phone.max' => 'El teléfono no puede exceder 20 caracteres.',
        ];
    }
}
