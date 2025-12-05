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
        'name' => 'Equipo ' . fake()->unique()->word,
        // event_id y leader_id se pasarán al crear
        'advisor_status' => 'pending',
    ];
}
}