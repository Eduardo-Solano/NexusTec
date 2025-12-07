<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
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
            'team_id' => 'required|exists:teams,id',
            'name' => 'required|string|max:100',
            'description' => 'required|string|max:1000',
            'repository_url' => 'required|url',
            'documentation' => 'nullable|file|mimes:pdf|max:10240', // Máx 10MB
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120', // Máx 5MB
            'video_url' => 'nullable|url',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'team_id.required' => 'El equipo es obligatorio.',
            'team_id.exists' => 'El equipo seleccionado no existe.',
            'name.required' => 'El nombre del proyecto es obligatorio.',
            'name.max' => 'El nombre no puede exceder 100 caracteres.',
            'description.required' => 'La descripción del proyecto es obligatoria.',
            'description.max' => 'La descripción no puede exceder 1000 caracteres.',
            'repository_url.required' => 'La URL del repositorio es obligatoria.',
            'repository_url.url' => 'La URL del repositorio debe ser una URL válida.',
            'documentation.mimes' => 'La documentación debe ser un archivo PDF.',
            'documentation.max' => 'La documentación no puede exceder 10MB.',
            'image.image' => 'El archivo debe ser una imagen.',
            'image.mimes' => 'La imagen debe ser JPEG, PNG, JPG o WebP.',
            'image.max' => 'La imagen no puede exceder 5MB.',
            'video_url.url' => 'La URL del video debe ser una URL válida.',
        ];
    }
}
