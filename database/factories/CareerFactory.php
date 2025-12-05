<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Career>
 */
class CareerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
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
