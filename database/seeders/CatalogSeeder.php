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
        // 1. Criterios
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

        // 2. Carreras del TecNM
        $carreras = [
            // Ingenierías
            ['name' => 'Ingeniería en Sistemas Computacionales', 'code' => 'ISC'],
            ['name' => 'Ingeniería en Gestión Empresarial', 'code' => 'IGE'],
            ['name' => 'Ingeniería Industrial', 'code' => 'IND'],
            ['name' => 'Ingeniería Electrónica', 'code' => 'IET'],
            ['name' => 'Ingeniería Civil', 'code' => 'IC'],
            ['name' => 'Ingeniería Mecánica', 'code' => 'IM'],
            ['name' => 'Ingeniería Eléctrica', 'code' => 'IE'],
            ['name' => 'Ingeniería Mecatrónica', 'code' => 'IMT'],
            ['name' => 'Ingeniería Química', 'code' => 'IQ'],
            ['name' => 'Ingeniería Bioquímica', 'code' => 'IBQ'],
            ['name' => 'Ingeniería en Tecnologías de la Información y Comunicaciones', 'code' => 'ITIC'],
            ['name' => 'Ingeniería en Logística', 'code' => 'ILOG'],
            ['name' => 'Ingeniería Ambiental', 'code' => 'IA'],
            ['name' => 'Ingeniería en Energías Renovables', 'code' => 'IER'],
            ['name' => 'Ingeniería en Materiales', 'code' => 'IMAT'],
            ['name' => 'Ingeniería en Nanotecnología', 'code' => 'INANO'],
            ['name' => 'Ingeniería en Aeronáutica', 'code' => 'IAERO'],
            ['name' => 'Ingeniería en Agronomía', 'code' => 'IAGRO'],
            ['name' => 'Ingeniería en Industrias Alimentarias', 'code' => 'IIA'],
            ['name' => 'Ingeniería Forestal', 'code' => 'IF'],
            // Licenciaturas
            ['name' => 'Licenciatura en Administración', 'code' => 'LA'],
            ['name' => 'Licenciatura en Contaduría', 'code' => 'LC'],
            ['name' => 'Licenciatura en Turismo', 'code' => 'LT'],
            ['name' => 'Licenciatura en Biología', 'code' => 'LB'],
            // Arquitectura
            ['name' => 'Arquitectura', 'code' => 'ARQ'],
        ];
        foreach ($carreras as $carrera) {
            Career::firstOrCreate(['code' => $carrera['code']], $carrera);
        }

        // 3. Especialidades
        $especialidades = [
            ['name' => 'Desarrollo de Software / Web'],
            ['name' => 'Ciberseguridad y Redes'],
            ['name' => 'Inteligencia Artificial y Ciencia de Datos'],
            ['name' => 'Gestión de Proyectos de Software'],
        ];
        foreach ($especialidades as $sp) {
            Specialty::firstOrCreate($sp);
        }
    }
}