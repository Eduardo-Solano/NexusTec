<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class AssignJudgeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'judge_id' => 'required|exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'judge_id.required' => 'Debes seleccionar un juez.',
            'judge_id.exists' => 'El juez seleccionado no existe.',
        ];
    }
}
