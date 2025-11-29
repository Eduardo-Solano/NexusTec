<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeamFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => 'Equipo ' . $this->faker->unique()->word, // Ej: "Equipo Alpha"
            // Crea un evento y un líder automáticamente si no se los pasas
            'event_id' => Event::factory(), 
            'leader_id' => User::factory(),
        ];
    }
}