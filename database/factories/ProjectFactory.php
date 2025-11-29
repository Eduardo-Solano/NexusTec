<?php

namespace Database\Factories;

use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => 'Proyecto ' . $this->faker->words(3, true),
            'description' => $this->faker->text(),
            'repository_url' => 'https://github.com/nexustec/repo-prueba',
            'team_id' => Team::factory(),
        ];
    }
}