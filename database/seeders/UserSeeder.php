<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\StudentProfile;
use App\Models\StaffProfile;
use App\Models\JudgeProfile;
use App\Models\Career;
use App\Models\Specialty;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // =========================================================
        // 1. USUARIOS FIJOS (Para tus pruebas manuales)
        // =========================================================

        // 1.1 ADMIN
        $admin = User::firstOrCreate(
            ['email' => 'admin@nexustec.com'],
            [
                'name' => 'Admin NexusTec',
                'password' => Hash::make('admin123'),
                'is_active' => true,
            ]
        );
        $admin->assignRole('admin');

        // 1.2 STAFF DE PRUEBA (EMP-001)
        $docente = User::firstOrCreate(
            ['email' => 'docente@nexustec.com'],
            [
                'name' => 'Ing. Docente Prueba',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );
        $docente->assignRole(['staff', 'advisor']); // Le ponemos ambos para probar
        
        StaffProfile::updateOrCreate(
            ['user_id' => $docente->id],
            [
                'employee_number' => 'EMP-001',
                'department' => 'Sistemas y Computación'
            ]
        );

        // 1.3 JUEZ DE PRUEBA (Para evaluar eventos activos)
        $juezPrueba = User::firstOrCreate(
            ['email' => 'juez@nexustec.com'],
            [
                'name' => 'Ing. Juez Prueba',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );
        $juezPrueba->assignRole('judge');

        JudgeProfile::updateOrCreate(
            ['user_id' => $juezPrueba->id],
            [
                'specialty_id' => Specialty::first()->id ?? Specialty::factory()->create()->id,
                'company' => 'NexusTec Partners',
            ]
        );

        // =========================================================
        // 2. GENERACIÓN MASIVA DE PERSONAL (Staff/Advisors)
        // =========================================================
        
        // Obtenemos los nombres de las carreras para usarlos como departamentos
        $departments = Career::pluck('name')->toArray();
        // Agregamos algunos departamentos administrativos extra
        $departments = array_merge($departments, ['Ciencias Básicas', 'Dirección Académica', 'Vinculación']);

        // Generamos 30 usuarios de personal
        for ($i = 2; $i <= 31; $i++) {
            // Formato EMP-002, EMP-003, etc.
            $empNumber = 'EMP-' . str_pad($i, 3, '0', STR_PAD_LEFT);
            
            $user = User::factory()->create([
                'password' => Hash::make('password'), // Todos con 'password'
                'is_active' => true,
            ]);

            // Lógica de Roles (Simulación de tipos de personal)
            // 40% Solo Organizadores (staff)
            // 40% Solo Docentes (advisor)
            // 20% Ambos (staff y advisor)
            $rand = rand(1, 100);
            
            if ($rand <= 40) {
                $user->assignRole('staff'); // Es administrativo
            } elseif ($rand <= 80) {
                $user->assignRole('advisor'); // Es docente
            } else {
                $user->assignRole(['staff', 'advisor']); // Es todólogo
            }

            // Crear Perfil de Staff
            StaffProfile::create([
                'user_id' => $user->id,
                'employee_number' => $empNumber,
                'department' => $departments[array_rand($departments)], // Depto aleatorio de la lista real
            ]);
        }

        // =========================================================
        // 3. POOL DE JUECES (Externos)
        // =========================================================
        $specialties = Specialty::all();
        
        User::factory(20)->create()->each(function ($user) use ($specialties) {
            $user->assignRole('judge');
            JudgeProfile::create([
                'user_id' => $user->id,
                'specialty_id' => $specialties->random()->id,
                'company' => fake()->company,
            ]);
        });

        // =========================================================
        // 4. ESTUDIANTES SIN EQUIPO (50 Estudiantes sueltos)
        // =========================================================
        $careers = Career::all();
        
        User::factory(50)->create()->each(function ($user) use ($careers) {
            $user->assignRole('student');
            StudentProfile::factory()->create([
                'user_id' => $user->id,
                'career_id' => $careers->random()->id,
                // El control_number se genera en el Factory del perfil o aquí si lo prefieres
            ]);
        });
    }
}