<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CareerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement([
                'Ingeniería en Sistemas Computacionales',
                'Ingeniería Industrial',
                'Ingeniería Electrónica',
                'Ingeniería Mecánica',
                'Ingeniería Civil',
                'Ingeniería Química',
                'Ingeniería en Gestión Empresarial',
                'Licenciatura en Administración',
            ]),
        ];
    }
}
