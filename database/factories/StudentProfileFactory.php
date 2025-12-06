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
            // Generar número de control con formato YY16NNNN
            // YY: Año de ingreso (20 al 25)
            // 16: Número de plantel fijo
            // NNNN: Consecutivo aleatorio (0901 al 1299)
            'control_number' => function() {
                $attempts = 0;
                do {
                    $year = fake()->numberBetween(20, 25);
                    $plantel = '16';
                    $consecutivo = fake()->numberBetween(901, 1299);
                    $consecutivoStr = str_pad($consecutivo, 4, '0', STR_PAD_LEFT);
                    $cn = "{$year}{$plantel}{$consecutivoStr}";
                    
                    // Verificar si existe en la base de datos (y en memoria local si hiciéramos batch)
                    // Como es seeder, podemos verificar directo en DB.
                    $exists = \App\Models\StudentProfile::where('control_number', $cn)->exists();
                    $attempts++;
                } while ($exists && $attempts < 100);

                if ($exists) {
                     // Fallback: usar timestamp o algo que quepa en 10 chars?
                     // 10 chars is tight. 
                     // Si fallamos 100 veces, algo está mal. Retornamos algo random esperando que pase.
                     return fake()->numerify('##16####'); 
                }
                return $cn;
            }, 
            'semester' => fake()->numberBetween(1, 10),
            // Asociar carrera existente o crear una nueva
            'career_id' => Career::inRandomOrder()->first()->id ?? Career::factory(),
            'user_id' => User::factory(),
        ];
    }
}
