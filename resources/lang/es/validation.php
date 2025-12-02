<?php

return [
    'required' => 'El campo :attribute es obligatorio.',
    'email' => 'El campo :attribute debe ser un correo válido.',
    'unique' => 'El valor en :attribute ya existe.',
    'min' => [
        'numeric' => 'El campo :attribute debe ser mínimo :min.',
        'file' => 'El campo :attribute debe ser mínimo :min kilobytes.',
        'string' => 'El campo :attribute debe tener al menos :min caracteres.',
        'array' => 'El campo :attribute debe tener al menos :min elementos.',
    ],
    'max' => [
        'numeric' => 'El campo :attribute no puede ser mayor que :max.',
        'file' => 'El campo :attribute no puede ser mayor que :max kilobytes.',
        'string' => 'El campo :attribute no puede tener más de :max caracteres.',
        'array' => 'El campo :attribute no puede tener más de :max elementos.',
    ],
    'confirmed' => 'La confirmación de :attribute no coincide.',
    'exists' => 'El campo :attribute seleccionado no existe.',
    'string' => 'El campo :attribute debe ser una cadena de texto.',
    'current_password' => 'La contraseña actual es incorrecta.',

    'attributes' => [
        'name' => 'Nombre Completo',
        'email' => 'Correo Electrónico',
        'password' => 'Contraseña',
        'password_confirmation' => 'Confirmación de Contraseña',
        'employee_number' => 'Número de Empleado',
        'department' => 'Departamento',
        'control_number' => 'Número de Control',
        'career_id' => 'Carrera',
    ],
];
