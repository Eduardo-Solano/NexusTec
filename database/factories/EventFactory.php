<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(3), // Ej: "Gran Hackathon 2025"
            'description' => $this->faker->paragraph(),
            // Fechas aleatorias entre hoy y dentro de un mes
            'start_date' => $this->faker->dateTimeBetween('now', '+1 month'), 
            'end_date' => $this->faker->dateTimeBetween('+1 month', '+2 months'),
            'is_active' => true,
        ];
    }
}