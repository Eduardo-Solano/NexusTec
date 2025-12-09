<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Career;
use App\Models\User;

class StudentProfileFactory extends Factory
{
    public function definition(): array
    {
        return [
            'control_number' => function () {
                $attempts = 0;
                do {
                    $year = fake()->numberBetween(20, 25);
                    $plantel = '16';
                    $consecutivo = fake()->numberBetween(901, 1299);
                    $consecutivoStr = str_pad($consecutivo, 4, '0', STR_PAD_LEFT);
                    $cn = "{$year}{$plantel}{$consecutivoStr}";
                    
                    $exists = \App\Models\StudentProfile::where('control_number', $cn)->exists();
                    $attempts++;
                } while ($exists && $attempts < 100);

                if ($exists) {
                     return fake()->numerify('##16####'); 
                }
                return $cn;
            }, 
            'semester' => fake()->numberBetween(1, 10),
            'career_id' => Career::inRandomOrder()->first()->id ?? Career::factory(),
            'user_id' => User::factory(),
        ];
    }
}
