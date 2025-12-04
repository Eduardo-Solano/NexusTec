<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Project;
use App\Models\User;
use App\Models\Team;
use App\Models\Award;
use App\Exports\RankingsExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class ExportController extends Controller
{
    public function __construct()
    {
        // Solo admin/staff pueden exportar reportes y diplomas
        $this->middleware('permission:reports.download');
    }

    /**
     * Obtener datos de rankings para exportación
     */
    private function getRankingsData(Event $event)
    {
        // Cargar criterios del evento
        $event->load('criteria');
        $criteria = $event->criteria;

        // Obtener todos los proyectos del evento con sus evaluaciones
        $projects = Project::whereHas('team', function($query) use ($event) {
                $query->where('event_id', $event->id);
            })
            ->with([
                'team.leader',
                'team.members',
                'evaluations.criterion',
                'evaluations.judge',
                'judges'
            ])
            ->get();

        // Calcular estadísticas para cada proyecto
        $projectsWithStats = $projects->map(function($project) use ($criteria) {
            $evaluations = $project->evaluations;
            
            $judgesCompleted = $project->judges()->wherePivot('is_completed', true)->count();
            $judgesTotal = $project->judges()->count();
            
            $scoresByCriterion = [];
            foreach ($criteria as $criterion) {
                $criterionEvaluations = $evaluations->where('criterion_id', $criterion->id);
                $scoresByCriterion[$criterion->id] = [
                    'name' => $criterion->name,
                    'max_points' => $criterion->max_points,
                    'average' => $criterionEvaluations->count() > 0 
                        ? round($criterionEvaluations->avg('score'), 2) 
                        : null,
                    'count' => $criterionEvaluations->count(),
                ];
            }
            
            $totalScore = collect($scoresByCriterion)->sum('average') ?? 0;
            $maxPossible = $criteria->sum('max_points');
            $percentage = $maxPossible > 0 ? round(($totalScore / $maxPossible) * 100, 1) : 0;

            return [
                'project' => $project,
                'team' => $project->team,
                'scores_by_criterion' => $scoresByCriterion,
                'total_score' => $totalScore,
                'max_possible' => $maxPossible,
                'percentage' => $percentage,
                'judges_completed' => $judgesCompleted,
                'judges_total' => $judgesTotal,
                'is_fully_evaluated' => $judgesTotal > 0 && $judgesCompleted === $judgesTotal,
            ];
        });

        $rankedProjects = $projectsWithStats->sortByDesc('total_score')->values();

        $stats = [
            'total_projects' => $projects->count(),
            'fully_evaluated' => $projectsWithStats->where('is_fully_evaluated', true)->count(),
            'pending_evaluation' => $projectsWithStats->where('is_fully_evaluated', false)->count(),
            'average_score' => $rankedProjects->avg('percentage') ?? 0,
        ];

        return compact('rankedProjects', 'criteria', 'stats');
    }

    /**
     * Exportar rankings a Excel
     */
    public function rankingsExcel(Event $event)
    {
        // Validar acceso
        $user = Auth::user();
        if (!$user->hasAnyRole(['admin', 'staff'])) {
            abort(403, 'No tienes permiso para exportar rankings.');
        }

        $data = $this->getRankingsData($event);
        
        $filename = 'rankings_' . str_replace(' ', '_', $event->name) . '_' . now()->format('Y-m-d') . '.xlsx';
        
        return Excel::download(
            new RankingsExport($event, $data['rankedProjects'], $data['criteria']),
            $filename
        );
    }

    /**
     * Exportar rankings a PDF
     */
    public function rankingsPdf(Event $event)
    {
        // Validar acceso
        $user = Auth::user();
        if (!$user->hasAnyRole(['admin', 'staff'])) {
            abort(403, 'No tienes permiso para exportar rankings.');
        }

        $data = $this->getRankingsData($event);
        
        $pdf = Pdf::loadView('exports.rankings-pdf', [
            'event' => $event,
            'rankedProjects' => $data['rankedProjects'],
            'criteria' => $data['criteria'],
            'stats' => $data['stats'],
        ]);

        $pdf->setPaper('A4', 'landscape');
        
        $filename = 'rankings_' . str_replace(' ', '_', $event->name) . '_' . now()->format('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Generar diploma de participación para un miembro del equipo
     */
    public function diplomaParticipation(Event $event, User $user)
    {
        // Validar acceso - solo admin/staff o el propio usuario si es participante
        $authUser = Auth::user();
        $isParticipant = Team::where('event_id', $event->id)
            ->whereHas('members', fn($q) => $q->where('user_id', $user->id))
            ->exists();

        if (!$authUser->hasAnyRole(['admin', 'staff']) && $authUser->id !== $user->id) {
            abort(403, 'No tienes permiso para generar este diploma.');
        }

        if (!$isParticipant) {
            abort(404, 'Este usuario no participó en el evento.');
        }

        // Obtener datos del equipo y proyecto
        $team = Team::where('event_id', $event->id)
            ->whereHas('members', fn($q) => $q->where('user_id', $user->id))
            ->with('project')
            ->first();

        $pdf = Pdf::loadView('exports.diploma', [
            'event' => $event,
            'participant' => $user,
            'team' => $team,
            'project' => $team->project ?? null,
            'type' => 'participation',
            'award' => null,
        ]);

        $pdf->setPaper('A4', 'landscape');
        
        $filename = 'diploma_' . str_replace(' ', '_', $user->name) . '_' . now()->format('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Generar diploma de ganador para un equipo premiado
     */
    public function diplomaWinner(Award $award, User $user)
    {
        // Validar acceso
        $authUser = Auth::user();
        $team = $award->team;
        $event = $award->event;
        
        $isMember = $team->members->contains('id', $user->id);

        if (!$authUser->hasAnyRole(['admin', 'staff']) && $authUser->id !== $user->id) {
            abort(403, 'No tienes permiso para generar este diploma.');
        }

        if (!$isMember) {
            abort(404, 'Este usuario no es miembro del equipo ganador.');
        }

        $pdf = Pdf::loadView('exports.diploma', [
            'event' => $event,
            'participant' => $user,
            'team' => $team,
            'project' => $team->project ?? null,
            'type' => 'winner',
            'award' => $award,
        ]);

        $pdf->setPaper('A4', 'landscape');
        
        $filename = 'diploma_ganador_' . str_replace(' ', '_', $user->name) . '_' . now()->format('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Vista para seleccionar diplomas a generar
     */
    public function diplomasIndex(Event $event)
    {
        $user = Auth::user();
        if (!$user->hasAnyRole(['admin', 'staff'])) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        $event->load(['teams.members', 'teams.project', 'awards.team.members']);

        return view('exports.diplomas-index', compact('event'));
    }
}
