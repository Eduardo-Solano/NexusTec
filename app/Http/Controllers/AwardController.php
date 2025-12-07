<?php

namespace App\Http\Controllers;

use App\Models\Award;
use App\Models\Event;
use App\Models\Team;
use App\Models\ActivityLog;
use App\Http\Requests\Award\StoreAwardRequest;
use App\Http\Requests\Award\UpdateAwardRequest;
use App\Notifications\AwardWonNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\DB;

class AwardController extends Controller
{
    /**
     * Display a listing of awards for an event.
     */
    public function index(Request $request)
    {
        $eventId = $request->get('event_id');
        
        if ($eventId) {
            $event = Event::with(['awards.team.project', 'awards.team.leader'])->findOrFail($eventId);
            $awards = $event->awards->sortBy(function($award) {
                return array_search($award->position, Award::POSITIONS) ?: 99;
            });
            return view('awards.index', compact('awards', 'event'));
        }

        // Si no hay evento, mostrar todos los premios agrupados por evento
        $events = Event::with(['awards.team.project'])->has('awards')->orderBy('end_date', 'desc')->get();
        return view('awards.all', compact('events'));
    }

    /**
     * Show the form for creating a new award.
     */
    public function create(Request $request)
    {
        $event = Event::with(['teams.project', 'teams.leader'])->findOrFail($request->event_id);
        
        // Los premios SOLO se asignan cuando el evento ha finalizado (estado cerrado)
        if (!$event->allowsAwardsAndDiplomas()) {
            return redirect()->route('events.rankings', $event)
                ->with('error', 'Solo puedes asignar premios cuando el evento haya finalizado.');
        }
        
        // Solo equipos que tienen proyecto
        $teams = $event->teams()->has('project')->with(['project', 'leader'])->get();
        
        // Solo 3 posiciones
        $positions = Award::POSITIONS;

        return view('awards.create', compact('event', 'teams', 'positions'));
    }

    /**
     * Store a newly created award.
     */
    public function store(StoreAwardRequest $request)
    {
        $validated = $request->validated();

        // Los premios SOLO se asignan cuando el evento ha finalizado (estado cerrado)
        $event = Event::findOrFail($validated['event_id']);
        if (!$event->allowsAwardsAndDiplomas()) {
            return back()->with('error', 'Solo puedes asignar premios cuando el evento haya finalizado.');        
        }

        // Verificar que no exista ya este premio para el evento
        $existingAward = Award::where('event_id', $validated['event_id'])
            ->where('position', $validated['position'])
            ->first();
        
        $positionLabel = Award::POSITIONS[$validated['position']] ?? 'Premio';
        
        if ($existingAward) {
            return back()->with('error', "Ya existe un {$positionLabel} asignado para este evento.")->withInput();
        }

        $award = Award::create([
            'event_id' => $validated['event_id'],
            'team_id' => $validated['team_id'],
            'position' => $validated['position'],
            'awarded_at' => now(),
        ]);

        // Cargar relaciones necesarias para la notificación
        $award->load(['team.members', 'team.leader', 'team.project', 'event']);

        // Notificar a todos los miembros del equipo (incluyendo al líder)
        $teamMembers = $award->team->members;
        
        // Enviar notificación a cada miembro
        Notification::send($teamMembers, new AwardWonNotification($award));

        // Registrar actividad
        ActivityLog::log('awarded', "Premio '{$positionLabel}' asignado al equipo '{$award->team->name}'", $award, [
            'event_id' => $event->id,
            'event_name' => $event->name,
            'team_id' => $award->team_id,
            'team_name' => $award->team->name,
            'position' => $award->position,
        ]);

        return redirect()->route('awards.index', ['event_id' => $validated['event_id']])
            ->with('success', "¡{$positionLabel} asignado exitosamente! 🏆 Se notificó a los " . $teamMembers->count() . ' integrantes del equipo.');
    }

    /**
     * Generar ganadores automáticamente basado en puntajes
     */
    public function generateWinners(Event $event)
    {
        // Verificar que el evento esté cerrado
        if (!$event->allowsAwardsAndDiplomas()) {
            return back()->with('error', 'Solo puedes generar ganadores cuando el evento haya finalizado.');
        }

        // Verificar que no haya premios ya asignados
        if ($event->awards()->count() > 0) {
            return back()->with('error', 'Este evento ya tiene premios asignados. Elimínalos primero si deseas regenerarlos.');
        }

        // Obtener proyectos con sus puntajes promedio
        $rankings = $event->teams()
            ->has('project')
            ->with(['project.evaluations', 'members'])
            ->get()
            ->map(function ($team) {
                $evaluations = $team->project->evaluations ?? collect();
                $avgScore = $evaluations->count() > 0 ? $evaluations->avg('score') : 0;
                $totalScore = $evaluations->count() > 0 ? $evaluations->sum('score') : 0;
                
                return [
                    'team' => $team,
                    'avg_score' => round($avgScore, 2),
                    'total_score' => $totalScore,
                    'evaluations_count' => $evaluations->count(),
                ];
            })
            ->filter(fn($item) => $item['evaluations_count'] > 0) // Solo equipos evaluados
            ->sortByDesc('total_score')
            ->take(3)
            ->values();

        if ($rankings->count() < 1) {
            return back()->with('error', 'No hay suficientes equipos evaluados para generar ganadores.');
        }

        $createdAwards = [];

        DB::transaction(function () use ($rankings, $event, &$createdAwards) {
            foreach ($rankings as $index => $ranking) {
                $positionKey = $index + 1; // 1, 2, 3
                $positionLabel = Award::POSITIONS[$positionKey] ?? null;
                if (!$positionLabel) continue;

                $award = Award::create([
                    'event_id' => $event->id,
                    'team_id' => $ranking['team']->id,
                    'position' => $positionKey,
                    'awarded_at' => now(),
                ]);

                $award->load(['team.members', 'team.leader', 'team.project', 'event']);

                // Notificar a todos los miembros del equipo
                $teamMembers = $ranking['team']->members;
                Notification::send($teamMembers, new AwardWonNotification($award));

                // Registrar actividad
                ActivityLog::log('awarded', "Premio '{$positionLabel}' asignado automáticamente al equipo '{$ranking['team']->name}' (Puntaje: {$ranking['total_score']})", $award, [
                    'event_id' => $event->id,
                    'event_name' => $event->name,
                    'team_id' => $ranking['team']->id,
                    'team_name' => $ranking['team']->name,
                    'position' => $positionKey,
                    'total_score' => $ranking['total_score'],
                    'auto_generated' => true,
                ]);

                $createdAwards[] = $award;
            }
        });

        $count = count($createdAwards);
        return redirect()->route('awards.index', ['event_id' => $event->id])
            ->with('success', "¡Se generaron {$count} ganadores automáticamente! 🏆🥈🥉 Se notificó a todos los equipos ganadores.");
    }

    /**
     * Display the specified award.
     */
    public function show(Award $award)
    {
        $award->load(['event', 'team.project', 'team.leader', 'team.members']);
        return view('awards.show', compact('award'));
    }

    /**
     * Show the form for editing the specified award.
     */
    public function edit(Award $award)
    {
        $award->load('event');
        $event = $award->event;
        $teams = $event->teams()->has('project')->with(['project', 'leader'])->get();
        
        $positions = Award::POSITIONS;

        return view('awards.edit', compact('award', 'event', 'teams', 'positions'));
    }

    /**
     * Update the specified award.
     */
    public function update(UpdateAwardRequest $request, Award $award)
    {
        $validated = $request->validated();

        // Verificar que no exista ya este premio para el evento (excepto el actual)
        $existingAward = Award::where('event_id', $award->event_id)
            ->where('position', $validated['position'])
            ->where('id', '!=', $award->id)
            ->first();
        
        $positionLabel = Award::POSITIONS[$validated['position']] ?? 'Premio';
        
        if ($existingAward) {
            return back()->with('error', "Ya existe un {$positionLabel} asignado para este evento.")->withInput();
        }

        $award->update([
            'team_id' => $validated['team_id'],
            'position' => $validated['position'],
        ]);

        return redirect()->route('awards.index', ['event_id' => $award->event_id])
            ->with('success', 'Premio actualizado correctamente.');
    }

    /**
     * Remove the specified award.
     */
    public function destroy(Award $award)
    {
        $eventId = $award->event_id;
        $award->delete();

        return redirect()->route('awards.index', ['event_id' => $eventId])
            ->with('success', 'Premio eliminado.');
    }
}
