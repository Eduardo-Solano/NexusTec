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
        $admin = User::firstOrCreate(
            ['email' => 'admin@nexustec.com'],
            [
                'name' => 'Admin NexusTec',
                'password' => Hash::make('admin123'),
                'is_active' => true,
            ]
        );
        $admin->assignRole('admin');

        $docente = User::firstOrCreate(
            ['email' => 'docente@nexustec.com'],
            [
                'name' => 'Ing. Docente Prueba',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );
        $docente->assignRole(['staff', 'advisor']);
        
        StaffProfile::updateOrCreate(
            ['user_id' => $docente->id],
            [
                'employee_number' => 'EMP-001',
                'department' => 'Sistemas y Computación'
            ]
        );

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

        $departments = Career::pluck('name')->toArray();
        $departments = array_merge($departments, ['Ciencias Básicas', 'Dirección Académica', 'Vinculación']);

        for ($i = 2; $i <= 31; $i++) {
            $empNumber = 'EMP-' . str_pad($i, 3, '0', STR_PAD_LEFT);
            
            $user = User::factory()->create([
                'password' => Hash::make('password'),
                'is_active' => true,
            ]);

            $rand = rand(1, 100);
            
            if ($rand <= 40) {
                $user->assignRole('staff');
            } elseif ($rand <= 80) {
                $user->assignRole('advisor');
            } else {
                $user->assignRole(['staff', 'advisor']);
            }

            StaffProfile::create([
                'user_id' => $user->id,
                'employee_number' => $empNumber,
                'department' => $departments[array_rand($departments)],
            ]);
        }

        $specialties = Specialty::all();
        
        User::factory(20)->create()->each(function ($user) use ($specialties) {
            $user->assignRole('judge');
            JudgeProfile::create([
                'user_id' => $user->id,
                'specialty_id' => $specialties->random()->id,
                'company' => fake()->company,
            ]);
        });

        $careers = Career::all();
        
        User::factory(50)->create()->each(function ($user) use ($careers) {
            $user->assignRole('student');
            StudentProfile::factory()->create([
                'user_id' => $user->id,
                'career_id' => $careers->random()->id,
            ]);
        });
    }
}