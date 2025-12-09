<?php

namespace App\Http\Requests\Career;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCareerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $careerId = $this->route('career')->id ?? $this->route('career');
        
        return [
            'name' => 'required|string|max:100|unique:careers,name,' . $careerId,
            'code' => 'required|string|max:20|unique:careers,code,' . $careerId,
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre de la carrera es obligatorio.',
            'name.max' => 'El nombre no puede exceder 100 caracteres.',
            'name.unique' => 'Ya existe otra carrera con este nombre.',
            'code.required' => 'El código de la carrera es obligatorio.',
            'code.max' => 'El código no puede exceder 20 caracteres.',
            'code.unique' => 'Ya existe otra carrera con este código.',
        ];
    }
}
