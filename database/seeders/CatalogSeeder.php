<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Career;
use App\Models\Specialty;
use App\Models\Criterion;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        $criterios = [
            ['name' => 'Innovación', 'max_points' => 30],
            ['name' => 'Complejidad Técnica', 'max_points' => 40],
            ['name' => 'Modelo de Negocio', 'max_points' => 30],
            ['name' => 'Impacto Social', 'max_points' => 20],
            ['name' => 'Viabilidad Financiera', 'max_points' => 20],
        ];
        foreach ($criterios as $crit) {
            Criterion::firstOrCreate($crit);
        }

        $carreras = [
            ['name' => 'Ingeniería en Sistemas Computacionales', 'code' => 'ISC'],
            ['name' => 'Ingeniería en Gestión Empresarial', 'code' => 'IGE'],
            ['name' => 'Ingeniería Industrial', 'code' => 'IND'],
            ['name' => 'Ingeniería Electrónica', 'code' => 'IET'],
        ];
        foreach ($carreras as $carrera) {
            Career::firstOrCreate($carrera);
        }

        $especialidades = [
            ['name' => 'Desarrollo de Software / Web'],
            ['name' => 'Ciberseguridad y Redes'],
            ['name' => 'Inteligencia Artificial y Ciencia de Datos'],
        ];
        foreach ($especialidades as $sp) {
            Specialty::firstOrCreate($sp);
        }
    }
}