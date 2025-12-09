<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\Team;
use App\Models\User;
use App\Models\Project;
use App\Models\Award;
use App\Models\Evaluation;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    //
    public function index()
    {
        $user = Auth::user();
        $data = [];

        if ($user->hasRole('admin') || $user->hasRole('staff') || $user->hasRole('advisor')) {
            // Lógica para ADMIN / STAFF / ADVISOR
            $data = [
                'total_students' => User::role('student')->count(),
                'active_events' => Event::whereIn('status', [Event::STATUS_REGISTRATION, Event::STATUS_ACTIVE])->count(),
                'total_teams' => Team::count(),
                'projects_delivered' => Project::count(),
                'recent_teams' => Team::with(['event', 'leader', 'members'])->latest()->take(5)->get(),
            ];

            // ========== NUEVO: Progreso del Evento Activo Principal ==========
            $activeEvent = Event::where('status', Event::STATUS_ACTIVE)
                ->where('end_date', '>', now()) // IMPORTANTE: Filtrar expirados por hora exacta
                ->withCount(['teams', 'criteria'])
                ->with(['teams.project.judges', 'teams.project.evaluations', 'awards'])
                ->orderBy('end_date', 'asc')
                ->first();

            if ($activeEvent) {
                $teamsWithProject = $activeEvent->teams->filter(fn($t) => $t->project !== null)->count();
                $totalTeams = $activeEvent->teams->count();

                // Calcular proyectos completamente evaluados
                $fullyEvaluated = 0;
                $totalEvaluations = 0;
                $requiredEvaluations = 0;

                foreach ($activeEvent->teams as $team) {
                    if ($team->project) {
                        $judgesCount = $team->project->judges()->count();
                        $completedJudges = $team->project->judges()->wherePivot('is_completed', true)->count();

                        if ($judgesCount > 0 && $completedJudges === $judgesCount) {
                            $fullyEvaluated++;
                        }

                        $totalEvaluations += $completedJudges;
                        $requiredEvaluations += $judgesCount;
                    }
                }

                $data['event_progress'] = [
                    'event' => $activeEvent,
                    'teams_count' => $totalTeams,
                    'projects_count' => $teamsWithProject,
                    'projects_percent' => $totalTeams > 0 ? round(($teamsWithProject / $totalTeams) * 100) : 0,
                    'evaluated_count' => $fullyEvaluated,
                    'evaluated_percent' => $teamsWithProject > 0 ? round(($fullyEvaluated / $teamsWithProject) * 100) : 0,
                    'total_evaluations' => $totalEvaluations,
                    'required_evaluations' => $requiredEvaluations,
                    'awards_count' => $activeEvent->awards->count(),
                    'days_remaining' => now()->diffInDays($activeEvent->end_date, false),
                    'hours_remaining' => now()->diffInHours($activeEvent->end_date, false) + (now()->diffInMinutes($activeEvent->end_date, false) % 60) / 60, // Horas con decimales para precisión
                ];
            }

            // Asesorías pendientes (solo para advisors)
            $data['pending_advisories'] = Team::where('advisor_id', $user->id)
                ->where('advisor_status', 'pending')
                ->with(['event', 'leader', 'members', 'project'])
                ->get();

            // 2. EQUIPOS ASESORADOS (Equipos aceptados con toda su info)
            $data['advised_teams'] = Team::where('advisor_id', $user->id)
                ->where('advisor_status', 'accepted')
                ->with(['event', 'leader', 'members', 'project.evaluations', 'project.judges', 'awards'])
                ->get();

            $data['my_projects'] = $data['advised_teams']->count();

            // Datos para gráficas - Equipos por día (últimos 14 días)
            $data['teams_by_day'] = Team::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->where('created_at', '>=', now()->subDays(14))
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            // Proyectos por evento
            $data['projects_by_event'] = Event::withCount('teams')
                ->orderBy('teams_count', 'desc')
                ->take(5)
                ->get();

            // Estudiantes por carrera (top 5)
            $data['students_by_career'] = DB::table('student_profiles')
                ->join('careers', 'student_profiles.career_id', '=', 'careers.id')
                ->select('careers.name', DB::raw('COUNT(*) as count'))
                ->groupBy('careers.id', 'careers.name')
                ->orderByDesc('count')
                ->take(5)
                ->get();
        } elseif ($user->hasRole('judge')) {
            // Lógica para JUEZ
            $assignedProjects = $user->assignedProjects()
                ->with(['team.event', 'team.members', 'team.leader', 'evaluations'])
                ->get();

            $pendingProjects = $assignedProjects->filter(fn($p) => !$p->pivot->is_completed);
            $completedProjects = $assignedProjects->filter(fn($p) => $p->pivot->is_completed);

            // Estadísticas de evaluaciones
            $totalEvaluations = Evaluation::where('judge_id', $user->id)->count();
            $avgScore = Evaluation::where('judge_id', $user->id)->avg('score');

            // Eventos en los que participa como juez
            $eventsAsJudge = Event::whereHas('teams.project.judges', function ($q) use ($user) {
                $q->where('judge_id', $user->id);
            })->with([
                        'teams' => function ($q) use ($user) {
                            $q->whereHas('project.judges', function ($q2) use ($user) {
                                $q2->where('judge_id', $user->id);
                            });
                        }
                    ])->get();

            $data = [
                'assigned_projects' => $assignedProjects,
                'pending_projects' => $pendingProjects,
                'completed_projects' => $completedProjects,
                'total_evaluations' => $totalEvaluations,
                'avg_score' => $avgScore ? round($avgScore, 2) : 0,
                'events_as_judge' => $eventsAsJudge,
                'pending_count' => $pendingProjects->count(),
                'completed_count' => $completedProjects->count(),
            ];
        } elseif ($user->hasRole('student')) {
            // Lógica para ESTUDIANTE

            // ✅ SOLO equipos donde el estudiante YA aceptó la invitación (is_accepted = true)
            $myTeams = $user->activeTeams()
                ->with(['event', 'project.evaluations', 'project.judges', 'members', 'awards'])
                ->latest()
                ->get();

            // Eventos próximos para mostrarle
            $upcomingEvents = Event::whereIn('status', [Event::STATUS_REGISTRATION, Event::STATUS_ACTIVE])
                ->where('start_date', '>', now())
                ->take(3)
                ->get();

            // ========== Calcular progreso de cada equipo ==========
            $teamsProgress = [];
            foreach ($myTeams as $team) {
                $steps = [
                    'registered' => ['label' => 'Equipo Registrado', 'completed' => true, 'icon' => '👥'],
                    'project' => ['label' => 'Proyecto Entregado', 'completed' => $team->project !== null, 'icon' => '📋'],
                    'judges' => ['label' => 'Jueces Asignados', 'completed' => $team->project && $team->project->judges()->count() > 0, 'icon' => '⚖️'],
                    'evaluated' => ['label' => 'Evaluación Completada', 'completed' => false, 'icon' => '✅'],
                    'results' => ['label' => 'Resultados Publicados', 'completed' => $team->awards->count() > 0, 'icon' => '🏆'],
                ];

                // Verificar si está completamente evaluado
                if ($team->project) {
                    $judgesCount = $team->project->judges()->count();
                    $completedJudges = $team->project->judges()->wherePivot('is_completed', true)->count();
                    $steps['evaluated']['completed'] = $judgesCount > 0 && $completedJudges === $judgesCount;
                }

                $completedSteps = collect($steps)->filter(fn($s) => $s['completed'])->count();
                $totalSteps = count($steps);

                $teamProgress = [
                    'steps' => $steps,
                    'completed' => $completedSteps,
                    'total' => $totalSteps,
                    'percent' => round(($completedSteps / $totalSteps) * 100),
                    'current_step' => collect($steps)->filter(fn($s) => !$s['completed'])->keys()->first() ?? 'results',
                ];

                // Calcular puntaje si está evaluado
                if ($team->project && $team->project->evaluations->count() > 0) {
                    $avgScore = $team->project->evaluations->avg('score');
                    $teamProgress['score'] = round($avgScore, 2);
                    $teamProgress['score_percent'] = $team->event->max_score > 0
                        ? round(($avgScore / $team->event->max_score) * 100)
                        : 0;
                }

                // Días restantes del evento
                if ($team->event) {
                    $teamProgress['days_remaining'] = now()->diffInDays($team->event->end_date, false);
                }

                $teamsProgress[$team->id] = $teamProgress;
            }

            $data = [
                'my_teams' => $myTeams,
                'teams_progress' => $teamsProgress,
                'upcoming_events' => $upcomingEvents,
            ];
        }

        return view('dashboard', compact('data'));
    }
}
