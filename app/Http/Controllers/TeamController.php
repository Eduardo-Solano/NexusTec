<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Event;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Notifications\TeamInvitationNotification;
use App\Notifications\TeamJoinRequestNotification;

class TeamController extends Controller
{
    /**
     * Crear equipo
     */
    public function index(Request $request)
    {
        // Obtener lista de eventos para el filtro
        $events = \App\Models\Event::orderBy('name')->get();

        $query = Team::with(['event', 'members', 'leader', 'advisor']);

        // Filtro por búsqueda (nombre del equipo o líder)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('leader', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filtro por evento
        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        $teams = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('teams.index', compact('teams', 'events'));
    }



    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'event_id' => 'required|exists:events,id',
            'leader_role' => 'required|string',
            'members' => 'array|max:4',
            'member_roles' => 'array',
            'members.*' => 'nullable|email|distinct',
            'advisor_id' => 'required|exists:users,id',
        ]);

        // ⛔ Validar que el evento esté abierto
        $event = Event::findOrFail($request->event_id);
        if ($event->isClosed()) {
            return back()->with('error', 'No se pueden registrar equipos porque el evento está cerrado o ha finalizado.');
        }

        // Validar correos
        $correosInvalidos = [];
        foreach (array_filter($request->members ?? []) as $email) {
            if (!User::where('email', $email)->exists()) {
                $correosInvalidos[] = $email;
            }
        }
        if ($correosInvalidos) {
            throw ValidationException::withMessages([
                'members' => 'Correos no válidos: ' . implode(', ', $correosInvalidos)
            ]);
        }

        return DB::transaction(function () use ($request, $event) {

            // Verificar si ya pertenece a un equipo del evento
            $exists = DB::table('team_user')
                ->join('teams', 'team_user.team_id', '=', 'teams.id')
                ->where('teams.event_id', $event->id)
                ->where('team_user.user_id', Auth::id())
                ->exists();

            if ($exists) {
                throw ValidationException::withMessages([
                    'event_id' => 'Ya estás en un equipo de este evento.'
                ]);
            }

            // Crear equipo
            $team = Team::create([
                'name' => $request->name,
                'event_id' => $event->id,
                'leader_id' => Auth::id(),
                'advisor_id' => $request->advisor_id,
                'advisor_status' => 'pending'
            ]);

            // LÍDER
            $team->members()->attach(Auth::id(), [
                'is_accepted' => true,
                'requested_by_user' => false,
                'role' => $request->leader_role
            ]);

            // MIEMBROS INVITADOS
            foreach (($request->members ?? []) as $index => $email) {
                if (!$email)
                    continue;

                $user = User::where('email', $email)->first();
                if (!$user || $user->id === Auth::id())
                    continue;

                $role = $request->member_roles[$index] ?? 'Miembro';

                $team->members()->attach($user->id, [
                    'is_accepted' => false,
                    'requested_by_user' => false,
                    'role' => $role
                ]);

                // Notificación de invitación
                $user->notify(new TeamInvitationNotification($team));
            }

            // Registrar actividad
            ActivityLog::log('created', "Equipo '{$team->name}' creado para el evento '{$event->name}'", $team, [
                'event_id' => $event->id,
                'event_name' => $event->name,
                'members_invited' => count(array_filter($request->members ?? [])),
            ]);

            return redirect()->route('events.show', $event)
                ->with('success', 'Equipo creado exitosamente.');
        });
    }

    /**
     * Mostrar equipo
     */
    public function show(Team $team)
    {
        $team->load(['event', 'members', 'leader', 'advisor', 'project']);
        $event = $team->event;
        return view('teams.show', compact('team', 'event'));
    }

    public function create(Request $request)
    {
        $event = Event::find($request->event_id);
        abort_unless($event, 404);

        // ⛔ Validar que el evento esté abierto
        if ($event->isClosed()) {
            return redirect()->route('events.show', $event)
                ->with('error', 'No se pueden crear equipos porque el evento está cerrado o ha finalizado.');
        }

        return view('teams.create', compact('event'));
    }

    /**
     * Formulario de edición de equipo
     */
    public function edit(Team $team)
    {
        $team->load(['event', 'members', 'leader', 'advisor', 'project']);
        
        // Obtener asesores disponibles (docentes)
        $advisors = User::role('advisor')->orderBy('name')->get();
        
        // Obtener eventos activos para el select
        $events = Event::where('is_active', true)->orderBy('name')->get();
        
        return view('teams.edit', compact('team', 'advisors', 'events'));
    }

    /**
     * Actualizar equipo
     */
    public function update(Request $request, Team $team)
    {
        $request->validate([
            'name' => 'required|string|max:50',
        ]);

        $team->update([
            'name' => $request->name,
        ]);

        return redirect()->route('teams.show', $team)
            ->with('success', 'Equipo actualizado correctamente.');
    }

    /**
     * Enviar solicitud para UNIRSE a un equipo
     */
    public function requestJoin(Request $request, Team $team)
    {
        $user = Auth::user();

        $request->validate([
            'role' => 'required|string'
        ]);

        // ⛔ Validar que el evento esté abierto
        if ($team->event->isClosed()) {
            return back()->with('error', 'No se pueden unir al equipo porque el evento está cerrado o ha finalizado.');
        }

        // Verificar si ya existe relación
        $existing = $team->members()->where('user_id', $user->id)->first();
        if ($existing) {
            if ($existing->pivot->is_accepted)
                return back()->with('error', 'Ya estás en este equipo.');

            if ($existing->pivot->requested_by_user)
                return back()->with('error', 'Ya enviaste una solicitud.');

            return back()->with('error', 'Tienes una invitación pendiente.');
        }

        // Crear solicitud
        $team->members()->attach($user->id, [
            'is_accepted' => false,
            'requested_by_user' => true,
            'role' => $request->role
        ]);

        // Notificar líder
        $team->leader->notify(new TeamJoinRequestNotification($team, $user));

        return back()->with('success', 'Solicitud enviada.');
    }

    /**
     * Aceptar solicitud o invitación (LÍDER o invitado)
     */
    public function accept(Team $team, User $user, Request $request)
    {
        // ⛔ Validar que el evento esté abierto
        if ($team->event->isClosed()) {
            return back()->with('error', 'No se pueden aceptar solicitudes porque el evento está cerrado o ha finalizado.');
        }

        if ($request->notification) {
            Auth::user()->notifications()->where('id', $request->notification)->update(['read_at' => now()]);
        }

        $team->members()->updateExistingPivot($user->id, [
            'is_accepted' => true
        ]);

        return back()->with('success', 'Miembro aceptado.');
    }


    /**
     * Rechazar solicitud o invitación
     */
    public function reject(Team $team, User $user, Request $request)
    {
        if ($request->notification) {
            Auth::user()->notifications()->where('id', $request->notification)->update(['read_at' => now()]);
        }

        $team->members()->detach($user->id);

        return back()->with('success', 'Solicitud rechazada.');
    }

    /**
     * Aceptar invitación (cuando el usuario actual es el invitado)
     */
    public function acceptInvitation(Team $team, $notification = null)
    {
        $user = Auth::user();

        // ⛔ Validar que el evento esté abierto
        if ($team->event->isClosed()) {
            return back()->with('error', 'No se pueden aceptar invitaciones porque el evento está cerrado o ha finalizado.');
        }

        // Marcar notificación como leída
        if ($notification) {
            $user->notifications()->where('id', $notification)->update(['read_at' => now()]);
        }

        // Verificar que el usuario tiene una invitación pendiente
        $member = $team->members()->where('user_id', $user->id)->first();

        if (!$member) {
            return back()->with('error', 'No tienes una invitación pendiente para este equipo.');
        }

        if ($member->pivot->is_accepted) {
            return back()->with('success', 'Ya eres miembro de este equipo.');
        }

        // Aceptar invitación
        $team->members()->updateExistingPivot($user->id, [
            'is_accepted' => true
        ]);

        return back()->with('success', '¡Te has unido al equipo exitosamente!');
    }

    /**
     * Rechazar invitación (cuando el usuario actual es el invitado)
     */
    public function rejectInvitation(Team $team, $notification = null)
    {
        $user = Auth::user();

        // Marcar notificación como leída
        if ($notification) {
            $user->notifications()->where('id', $notification)->update(['read_at' => now()]);
        }

        // Verificar que el usuario tiene una invitación pendiente
        $member = $team->members()->where('user_id', $user->id)->first();

        if (!$member) {
            return back()->with('error', 'No tienes una invitación pendiente para este equipo.');
        }

        // Rechazar invitación (remover de la tabla pivot)
        $team->members()->detach($user->id);

        return back()->with('success', 'Invitación rechazada.');
    }

    /**
     * Responder a solicitud de asesoría (aceptar o rechazar)
     */
    public function respondAdvisory(Team $team, string $status, Request $request)
    {
        $user = Auth::user();

        // ⛔ Validar que el evento esté abierto (solo para aceptar)
        if ($status === 'accepted' && $team->event->isClosed()) {
            return back()->with('error', 'No se pueden aceptar solicitudes de asesoría porque el evento está cerrado o ha finalizado.');
        }

        // Verificar que el usuario es el asesor solicitado
        if ($team->advisor_id !== $user->id) {
            return back()->with('error', 'No tienes permiso para responder a esta solicitud.');
        }

        // Verificar que la solicitud está pendiente
        if ($team->advisor_status !== 'pending') {
            return back()->with('error', 'Esta solicitud ya fue respondida.');
        }

        // Validar el status
        if (!in_array($status, ['accepted', 'rejected'])) {
            return back()->with('error', 'Estado inválido.');
        }

        // Marcar notificación como leída si existe
        if ($request->notification) {
            $user->notifications()->where('id', $request->notification)->update(['read_at' => now()]);
        }

        if ($status === 'accepted') {
            $team->update(['advisor_status' => 'accepted']);
            return back()->with('success', '¡Has aceptado ser asesor de este equipo!');
        } else {
            // Si rechaza, limpiar el advisor_id
            $team->update([
                'advisor_id' => null,
                'advisor_status' => null
            ]);
            return back()->with('success', 'Has rechazado la solicitud de asesoría.');
        }
    }

}
