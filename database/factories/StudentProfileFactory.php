<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use \App\Models\Career;
use \App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StudentProfile>
 */
class StudentProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Generar número de control aquí
            'control_number' => fake()->unique()->numerify('########'), 
            'semester' => fake()->numberBetween(1, 10),
            // Asociar carrera existente o crear una nueva
            'career_id' => Career::inRandomOrder()->first()->id ?? Career::factory(),
            'user_id' => User::factory(),
        ];
    }
}
