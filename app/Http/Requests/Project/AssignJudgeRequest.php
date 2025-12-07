<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class AssignJudgeRequest extends FormRequest
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
            'judge_id' => 'required|exists:users,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'judge_id.required' => 'Debes seleccionar un juez.',
            'judge_id.exists' => 'El juez seleccionado no existe.',
        ];
    }
}
