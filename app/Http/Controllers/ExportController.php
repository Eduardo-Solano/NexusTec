<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Project;
use App\Exports\RankingsExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class ExportController extends Controller
{
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
}
