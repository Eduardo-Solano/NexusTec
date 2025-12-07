<?php
namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Project;
use App\Models\User;
use App\Models\Team;
use App\Models\Award;
use App\Exports\RankingsExport;
use App\Notifications\DiplomaNotification;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use ZipArchive;

class ExportController extends Controller
{
    /**
     * Obtener datos de rankings para exportaciÃ³n
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

        // Calcular estadÃ­sticas para cada proyecto
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
     * Generar diploma de participaciÃ³n para un miembro del equipo
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
            abort(404, 'Este usuario no participÃ³ en el evento.');
        }

        // â›” Validar que el evento haya finalizado
        if (!$event->allowsAwardsAndDiplomas()) {
            return back()->with('error', 'Los diplomas solo pueden generarse cuando el evento ha finalizado.');
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

        // â›” Validar que el evento haya finalizado
        if (!$event->allowsAwardsAndDiplomas()) {
            return back()->with('error', 'Los diplomas solo pueden generarse cuando el evento ha finalizado.');
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
            abort(403, 'No tienes permiso para acceder a esta secciÃ³n.');
        }

        // â›” Validar que el evento haya finalizado
        if (!$event->allowsAwardsAndDiplomas()) {
            return redirect()->route('events.rankings', $event)
                ->with('error', 'Los diplomas solo pueden generarse cuando el evento ha finalizado.');
        }

        $event->load(['teams.members', 'teams.project', 'awards.team.members']);

        return view('exports.diplomas-index', compact('event'));
    }

    /**
     * Generar diplomas masivamente para un equipo (ZIP)
     */
    public function diplomasByTeam(Event $event, Team $team)
    {
        $user = Auth::user();
        if (!$user->hasAnyRole(['admin', 'staff'])) {
            abort(403, 'No tienes permiso para generar diplomas.');
        }

        // Validar que el evento haya finalizado
        if (!$event->allowsAwardsAndDiplomas()) {
            return back()->with('error', 'Los diplomas solo pueden generarse cuando el evento ha finalizado.');
        }

        // Validar que el equipo pertenece al evento
        if ($team->event_id !== $event->id) {
            abort(404, 'El equipo no pertenece a este evento.');
        }

        $team->load(['members', 'project']);
        
        // Crear ZIP temporal
        $zipFileName = 'diplomas_' . str_replace(' ', '_', $team->name) . '_' . now()->format('Y-m-d') . '.zip';
        $zipPath = storage_path('app/temp/' . $zipFileName);
        
        // Asegurar que el directorio existe
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return back()->with('error', 'No se pudo crear el archivo ZIP.');
        }

        foreach ($team->members as $member) {
            $pdf = Pdf::loadView('exports.diploma', [
                'event' => $event,
                'participant' => $member,
                'team' => $team,
                'project' => $team->project ?? null,
                'type' => 'participation',
                'award' => null,
            ]);
            $pdf->setPaper('A4', 'landscape');
            
            $pdfContent = $pdf->output();
            $pdfName = 'diploma_' . str_replace(' ', '_', $member->name) . '.pdf';
            $zip->addFromString($pdfName, $pdfContent);
        }

        $zip->close();

        return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);
    }

    /**
     * Generar TODOS los diplomas del evento (ZIP)
     */
    public function diplomasByEvent(Event $event)
    {
        $user = Auth::user();
        if (!$user->hasAnyRole(['admin', 'staff'])) {
            abort(403, 'No tienes permiso para generar diplomas.');
        }

        // Validar que el evento haya finalizado
        if (!$event->allowsAwardsAndDiplomas()) {
            return back()->with('error', 'Los diplomas solo pueden generarse cuando el evento ha finalizado.');
        }

        $event->load(['teams.members', 'teams.project', 'awards.team.members']);
        
        // Crear ZIP temporal
        $zipFileName = 'diplomas_' . str_replace(' ', '_', $event->name) . '_' . now()->format('Y-m-d') . '.zip';
        $zipPath = storage_path('app/temp/' . $zipFileName);
        
        // Asegurar que el directorio existe
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return back()->with('error', 'No se pudo crear el archivo ZIP.');
        }

        // 1. Diplomas de participaciÃ³n por equipo
        foreach ($event->teams as $team) {
            $teamFolder = 'participacion/' . str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '_', $team->name);
            
            foreach ($team->members as $member) {
                $pdf = Pdf::loadView('exports.diploma', [
                    'event' => $event,
                    'participant' => $member,
                    'team' => $team,
                    'project' => $team->project ?? null,
                    'type' => 'participation',
                    'award' => null,
                ]);
                $pdf->setPaper('A4', 'landscape');
                
                $pdfContent = $pdf->output();
                $pdfName = $teamFolder . '/diploma_' . str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '_', $member->name) . '.pdf';
                $zip->addFromString($pdfName, $pdfContent);
            }
        }

        // 2. Diplomas de ganadores
        foreach ($event->awards as $award) {
            $awardFolder = 'ganadores/' . str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '_', $award->category);
            
            foreach ($award->team->members as $member) {
                $pdf = Pdf::loadView('exports.diploma', [
                    'event' => $event,
                    'participant' => $member,
                    'team' => $award->team,
                    'project' => $award->team->project ?? null,
                    'type' => 'winner',
                    'award' => $award,
                ]);
                $pdf->setPaper('A4', 'landscape');
                
                $pdfContent = $pdf->output();
                $pdfName = $awardFolder . '/diploma_ganador_' . str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '_', $member->name) . '.pdf';
                $zip->addFromString($pdfName, $pdfContent);
            }
        }

        $zip->close();

        return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);
    }

    /**
     * Enviar diploma por correo a un participante especÃ­fico
     */
    public function sendDiplomaToUser(Request $request, Event $event, User $user)
    {
        $authUser = Auth::user();
        if (!$authUser->hasAnyRole(['admin', 'staff'])) {
            abort(403, 'No tienes permiso para enviar diplomas.');
        }

        // Validar que el evento haya finalizado
        if (!$event->allowsAwardsAndDiplomas()) {
            return back()->with('error', 'Los diplomas solo pueden enviarse cuando el evento ha finalizado.');
        }

        // Buscar el equipo del usuario en este evento
        $team = Team::where('event_id', $event->id)
            ->whereHas('members', fn($q) => $q->where('user_id', $user->id))
            ->with('project')
            ->first();

        if (!$team) {
            return back()->with('error', 'Este usuario no participÃ³ en el evento.');
        }

        $type = $request->input('type', 'participation');
        $award = null;

        if ($type === 'winner' && $request->has('award_id')) {
            $award = Award::find($request->input('award_id'));
        }

        // Enviar notificaciÃ³n con diploma
        $user->notify(new DiplomaNotification($event, $team, $award, $type));

        $tipoTexto = $type === 'winner' ? 'de ganador' : 'de participaciÃ³n';
        return back()->with('success', "Diploma {$tipoTexto} enviado por correo a {$user->name}.");
    }

    /**
     * Enviar diplomas por correo a todo un equipo
     */
    public function sendDiplomasToTeam(Event $event, Team $team)
    {
        $authUser = Auth::user();
        if (!$authUser->hasAnyRole(['admin', 'staff'])) {
            abort(403, 'No tienes permiso para enviar diplomas.');
        }

        // Validar que el evento haya finalizado
        if (!$event->allowsAwardsAndDiplomas()) {
            return back()->with('error', 'Los diplomas solo pueden enviarse cuando el evento ha finalizado.');
        }

        // Validar que el equipo pertenece al evento
        if ($team->event_id !== $event->id) {
            return back()->with('error', 'El equipo no pertenece a este evento.');
        }

        $team->load(['members', 'project']);
        $count = 0;

        foreach ($team->members as $member) {
            $member->notify(new DiplomaNotification($event, $team, null, 'participation'));
            $count++;
        }

        return back()->with('success', "Diplomas de participaciÃ³n enviados por correo a {$count} miembros del equipo {$team->name}.");
    }

    /**
     * Enviar TODOS los diplomas del evento por correo
     */
    public function sendDiplomasToEvent(Event $event)
    {
        $authUser = Auth::user();
        if (!$authUser->hasAnyRole(['admin', 'staff'])) {
            abort(403, 'No tienes permiso para enviar diplomas.');
        }

        // Validar que el evento haya finalizado
        if (!$event->allowsAwardsAndDiplomas()) {
            return back()->with('error', 'Los diplomas solo pueden enviarse cuando el evento ha finalizado.');
        }

        $event->load(['teams.members', 'teams.project', 'awards.team.members']);
        
        $participationCount = 0;
        $winnerCount = 0;

        // 1. Enviar diplomas de participaciÃ³n
        foreach ($event->teams as $team) {
            foreach ($team->members as $member) {
                $member->notify(new DiplomaNotification($event, $team, null, 'participation'));
                $participationCount++;
            }
        }

        // 2. Enviar diplomas de ganadores
        foreach ($event->awards as $award) {
            foreach ($award->team->members as $member) {
                $member->notify(new DiplomaNotification($event, $award->team, $award, 'winner'));
                $winnerCount++;
            }
        }

        $total = $participationCount + $winnerCount;
        return back()->with('success', "Se enviaron {$total} diplomas por correo ({$participationCount} de participaciÃ³n + {$winnerCount} de ganadores).");
    }
}

