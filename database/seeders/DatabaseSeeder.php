<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Career;
use App\Models\Event;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ejecutar el Seeder de Roles PRIMERO (Vital para Spatie)
        $this->call([
            RoleSeeder::class,
        ]);

        // 2. Crear Carreras (Catálogo real del Tec)
        $carreras = [
            ['name' => 'Ingeniería en Sistemas Computacionales', 'code' => 'ISC'],
            ['name' => 'Ingeniería en Gestión Empresarial', 'code' => 'IGE'],
            ['name' => 'Ingeniería Industrial', 'code' => 'IND'],
            ['name' => 'Ingeniería Electrónica', 'code' => 'IET'],
            ['name' => 'Ingeniería Electrica', 'code' => 'IEL'],
            ['name' => 'Ingeniería en Química', 'code' => 'IQQ'],
            ['name' => 'Ingeniería en Mecanica', 'code' => 'IME'],
            ['name' => 'Ingeniería en Civil', 'code' => 'ICV'],
            ['name' => 'Licenciatura en Administración', 'code' => 'ADM'],
            ['name' => 'Licenciatura en Contaduría Pública', 'code' => 'CP'],
        ];

        foreach ($carreras as $carrera) {
            Career::firstOrCreate($carrera);
        }

        // 2.5 Crear Especialidades (Para los Jueces)
        $especialidades = [
            ['name' => 'Desarrollo de Software / Web'],
            ['name' => 'Ciberseguridad y Redes'],
            ['name' => 'Inteligencia Artificial y Ciencia de Datos'],
            ['name' => 'Internet de las Cosas (IoT)'],
            ['name' => 'Innovación y Emprendimiento'],
            ['name' => 'Diseño UI/UX'],
        ];

        foreach ($especialidades as $sp) {
            \App\Models\Specialty::firstOrCreate($sp);
        }

        // 3. Crear TU Usuario Administrador (Para que puedas entrar)
        $admin = User::create([
            'name' => 'Admin NexusTec',
            'email' => 'admin@nexustec.com',
            'password' => Hash::make('password'), // Contraseña fácil para desarrollo
            'is_active' => true,
        ]);
        $admin->assignRole('admin');

        // 4. Crear un Docente/Organizador de prueba
        $docente = User::create([
            'name' => 'Ing. Docente Prueba',
            'email' => 'docente@nexustec.com',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);
        $docente->assignRole('staff');
        // Crear su perfil de Staff
        $docente->staffProfile()->create([
            'employee_number' => 'EMP-001',
            'department' => 'Sistemas y Computación'
        ]);

        // 5. Crear Datos Masivos (Factories)
        // Crea 1 Evento con 5 Equipos
        Event::factory()
            ->hasTeams(5) 
            ->create([
                'name' => 'HackTec 2025',
                'is_active' => true
            ]);
            
        // Crea 20 Alumnos sueltos con perfil
        User::factory(20)->create()->each(function ($user) {
            $user->assignRole('student');
            $user->studentProfile()->create([
                'career_id' => Career::inRandomOrder()->first()->id,
                'semester' => rand(1, 9)
            ]);
        });
    }
}