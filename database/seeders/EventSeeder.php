<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\Team;
use App\Models\User;
use App\Models\Project;
use App\Models\Criterion;
use App\Models\Evaluation;
use App\Models\StudentProfile;
use App\Models\Career;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $criterios = Criterion::all();
        $careers = Career::all();
        // Obtenemos los usuarios con rol advisor y judge que creamos en UserSeeder
        $advisors = User::role('advisor')->get();
        $judges = User::role('judge')->get();

        // ==========================================
        // PARTE 1: EVENTOS PASADOS (5 Eventos)
        // Tienen proyectos, repositorios, evaluaciones terminadas y ganadores
        // ==========================================
        
        $pastEvents = Event::factory(5)->create([
            'is_active' => false,
            'start_date' => now()->subMonths(6),
            'end_date' => now()->subMonths(5),
        ]);

        foreach ($pastEvents as $event) {
            // Asignar criterios al evento
            $event->criteria()->attach($criterios->pluck('id'));

            // Crear 6 equipos por evento (30 equipos pasados en total)
            // Esto genera estudiantes y los mete a equipos
            for ($i = 0; $i < 6; $i++) {
                $this->createCompleteTeam($event, $careers, $advisors, $judges, $criterios, true);
            }
        }

        // ==========================================
        // PARTE 2: EVENTOS ACTIVOS/FUTUROS (5 Eventos)
        // Equipos formados, proyectos iniciados (quizás sin repo), sin evaluaciones
        // ==========================================

        $activeEvents = Event::factory(5)->create([
            'is_active' => true,
            'start_date' => now()->subDays(1), // Empezó ayer
            'end_date' => now()->addDays(5),   // Termina en 5 días
        ]);

        foreach ($activeEvents as $event) {
            $event->criteria()->attach($criterios->pluck('id'));

            // Crear 6 equipos por evento (30 equipos activos en total)
            for ($i = 0; $i < 6; $i++) {
                $this->createCompleteTeam($event, $careers, $advisors, $judges, $criterios, false);
            }
        }
    }

    /**
     * Función auxiliar para crear toda la estructura de un equipo
     */
    private function createCompleteTeam($event, $careers, $advisors, $judges, $criterios, $isPast)
    {
        // 1. Crear Líder (Estudiante)
        $leader = User::factory()->create();
        $leader->assignRole('student');
        StudentProfile::factory()->create(['user_id' => $leader->id, 'career_id' => $careers->random()->id]);

        // 2. Elegir Asesor (A veces null para simular equipos sin asesor)
        // 70% de probabilidad de tener asesor
        $advisor = (rand(1, 100) <= 70) ? $advisors->random() : null;
        $advisorStatus = ($advisor && $isPast) ? 'accepted' : 'pending';

        // 3. Crear Equipo
        $team = Team::factory()->create([
            'event_id' => $event->id,
            'leader_id' => $leader->id,
            'advisor_id' => $advisor?->id,
            'advisor_status' => $advisorStatus,
        ]);

        // 4. Agregar al Líder a la tabla pivote team_user
        $team->users()->attach($leader->id, [
            'role' => 'leader',
            'is_accepted' => true,
            'requested_by_user' => true
        ]);

        // 5. Crear Miembros Extra (2 o 3 estudiantes más)
        $membersCount = rand(2, 3);
        $members = User::factory($membersCount)->create();
        
        foreach($members as $member) {
            $member->assignRole('student');
            StudentProfile::factory()->create(['user_id' => $member->id, 'career_id' => $careers->random()->id]);
            
            // Adjuntar al equipo
            $team->users()->attach($member->id, [
                'role' => 'member',
                'is_accepted' => true, // En la prueba asumimos que ya aceptaron
                'requested_by_user' => false
            ]);
        }

        // 6. Crear Proyecto
        $project = Project::factory()->create([
            'team_id' => $team->id,
            'repository_url' => $isPast ? 'https://github.com/nexustec/repo-prueba' : null,
        ]);

        // 7. SI ES PASADO: Asignar Jueces y Evaluar
        if ($isPast) {
            // Asignar 3 jueces aleatorios al proyecto
            $projectJudges = $judges->random(3);

            foreach ($projectJudges as $judge) {
                // Llenar tabla pivote judge_project
                // Asumimos que hay un modelo JudgeProject o relación many-to-many
                // Si usas belongsToMany en Project:
                //$project->judges()->attach($judge->id, ['is_completed' => true]); 
                // Si no tienes la relación directa en el modelo, usamos DB o el modelo intermedio:
                \Illuminate\Support\Facades\DB::table('judge_project')->insert([
                    'judge_id' => $judge->id,
                    'project_id' => $project->id,
                    'is_completed' => true,
                    'assigned_at' => now()->subMonths(5),
                ]);

                // Crear Evaluaciones (Una por cada criterio del evento)
                foreach ($criterios as $criterion) {
                    Evaluation::factory()->create([
                        'project_id' => $project->id,
                        'judge_id' => $judge->id,
                        'criterion_id' => $criterion->id,
                        'score' => rand(50, 100), // Puntos entre 50 y 100
                        'feedback' => fake()->sentence,
                    ]);
                }
            }
        }
    }
}