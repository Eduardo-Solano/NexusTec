<?php

namespace App\Http\Controllers;

use App\Models\Award;
use App\Models\Event;
use App\Models\Team;
use App\Models\ActivityLog;
use App\Notifications\AwardWonNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

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
            $awards = $event->awards;
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
        
        // Categorías predefinidas
        $categories = [
            '1er Lugar' => '🥇 1er Lugar',
            '2do Lugar' => '🥈 2do Lugar',
            '3er Lugar' => '🥉 3er Lugar',
            'Mención Honorífica' => '🏅 Mención Honorífica',
            'Mejor Innovación' => '💡 Mejor Innovación',
            'Mejor Diseño' => '🎨 Mejor Diseño',
            'Mejor Presentación' => '🎤 Mejor Presentación',
            'Premio del Público' => '👥 Premio del Público',
            'Otro' => '🏆 Otro',
        ];

        return view('awards.create', compact('event', 'teams', 'categories'));
    }

    /**
     * Store a newly created award.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'team_id' => 'required|exists:teams,id',
            'category' => 'required|string|max:100',
            'name' => 'nullable|string|max:255',
        ]);

        // Los premios SOLO se asignan cuando el evento ha finalizado (estado cerrado)
        $event = Event::findOrFail($validated['event_id']);
        if (!$event->allowsAwardsAndDiplomas()) {
            return back()->with('error', 'Solo puedes asignar premios cuando el evento haya finalizado.');        
        }

        // Si la categoría es "Otro", usar el nombre personalizado
        $awardName = $validated['category'] === 'Otro' && $validated['name'] 
            ? $validated['name'] 
            : $validated['category'];

        $award = Award::create([
            'event_id' => $validated['event_id'],
            'team_id' => $validated['team_id'],
            'name' => $awardName,
            'category' => $validated['category'],
            'awarded_at' => now(),
        ]);

        // Cargar relaciones necesarias para la notificación
        $award->load(['team.members', 'team.leader', 'team.project', 'event']);

        // Notificar a todos los miembros del equipo (incluyendo al líder)
        $teamMembers = $award->team->members;
        
        // Enviar notificación a cada miembro
        Notification::send($teamMembers, new AwardWonNotification($award));

        // Registrar actividad
        ActivityLog::log('awarded', "Premio '{$awardName}' asignado al equipo '{$award->team->name}'", $award, [
            'event_id' => $event->id,
            'event_name' => $event->name,
            'team_id' => $award->team_id,
            'team_name' => $award->team->name,
            'category' => $awardName,
        ]);

        return redirect()->route('awards.index', ['event_id' => $validated['event_id']])
            ->with('success', '¡Premio asignado exitosamente! 🏆 Se notificó a los ' . $teamMembers->count() . ' integrantes del equipo.');
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
        
        $categories = [
            '1er Lugar' => '🥇 1er Lugar',
            '2do Lugar' => '🥈 2do Lugar',
            '3er Lugar' => '🥉 3er Lugar',
            'Mención Honorífica' => '🏅 Mención Honorífica',
            'Mejor Innovación' => '💡 Mejor Innovación',
            'Mejor Diseño' => '🎨 Mejor Diseño',
            'Mejor Presentación' => '🎤 Mejor Presentación',
            'Premio del Público' => '👥 Premio del Público',
            'Otro' => '🏆 Otro',
        ];

        return view('awards.edit', compact('award', 'event', 'teams', 'categories'));
    }

    /**
     * Update the specified award.
     */
    public function update(Request $request, Award $award)
    {
        $validated = $request->validate([
            'team_id' => 'required|exists:teams,id',
            'category' => 'required|string|max:100',
            'name' => 'nullable|string|max:255',
        ]);

        $awardName = $validated['category'] === 'Otro' && $validated['name'] 
            ? $validated['name'] 
            : $validated['category'];

        $award->update([
            'team_id' => $validated['team_id'],
            'name' => $awardName,
            'category' => $validated['category'],
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
