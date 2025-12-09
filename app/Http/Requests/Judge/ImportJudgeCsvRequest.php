<?php

namespace App\Http\Requests\Judge;

use Illuminate\Foundation\Http\FormRequest;

class ImportJudgeCsvRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'csv_file' => 'required|mimes:csv,txt',
        ];
    }

    public function messages(): array
    {
        return [
            'csv_file.required' => 'El archivo CSV es obligatorio.',
            'csv_file.mimes' => 'El archivo debe ser de tipo CSV o TXT.',
        ];
    }
}
