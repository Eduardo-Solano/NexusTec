<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Roles y Catálogos (Base del sistema)
        $this->call([
            RoleSeeder::class,
            CatalogSeeder::class, // Carreras, Especialidades, Criterios
        ]);

        // 2. Usuarios independientes (Admin, Staff, Estudiantes sin equipo, Pool de Asesores/Jueces)
        $this->call(UserSeeder::class);

        // 3. Eventos y Lógica Compleja (Equipos, Proyectos, Evaluaciones)
        $this->call(EventSeeder::class);
    }
}