<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Notifications\TeamInvitationNotification;

class TeamController extends Controller
{
    /**
     * Crear equipo
     */
    public function store(Request $request)
    {
        // Validación base
        $request->validate([
            'name' => 'required|string|max:50',
            'event_id' => 'required|exists:events,id',
            'leader_role' => 'required|string',
            'members' => 'array|max:4',
            'member_roles' => 'array',
            'members.*' => 'nullable|email|distinct',
            'advisor_id' => 'required|exists:users,id',
        ]);

        // Validación personalizada de correos
        $memberEmails = array_filter($request->members ?? []);
        $correosInvalidos = [];

        foreach ($memberEmails as $email) {
            if (!User::where('email', $email)->exists()) {
                $correosInvalidos[] = $email;
            }
        }

        if (!empty($correosInvalidos)) {
            throw ValidationException::withMessages([
                'members' => 'Los siguientes correos no existen: ' . implode(', ', $correosInvalidos)
            ]);
        }

        return DB::transaction(function () use ($request) {

            $event = Event::findOrFail($request->event_id);

            // Verificar si el usuario YA está inscrito en un equipo del evento
            $existingEntry = DB::table('team_user')
                ->join('teams', 'team_user.team_id', '=', 'teams.id')
                ->where('teams.event_id', $event->id)
                ->where('team_user.user_id', Auth::id())
                ->exists();

            if ($existingEntry) {
                throw ValidationException::withMessages([
                    'event_id' => 'Ya estás registrado en un equipo de este evento.'
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

            // Agregar líder
            $team->members()->attach(Auth::id(), [
                'is_accepted' => true,
                'requested_by_user' => false,
                'role' => $request->leader_role
            ]);

            // Procesar miembros invitados
            $memberEmails = $request->members ?? [];
            $memberRoles = $request->member_roles ?? [];

            foreach ($memberEmails as $index => $email) {

                if (empty($email))
                    continue;

                $user = User::where('email', $email)->first();

                if (!$user)
                    continue;
                if ($user->id == Auth::id())
                    continue;

                $role = $memberRoles[$index] ?? 'Miembro';

                // INVITACIÓN (no solicitud)
                $team->members()->attach($user->id, [
                    'is_accepted' => false,
                    'requested_by_user' => false, // ← Aquí marcamos que fue el líder quien lo invitó
                    'role' => $role
                ]);

                // Enviar notificación
                $user->notify(new TeamInvitationNotification($team));
            }

            return redirect()->route('events.show', $event)
                ->with('success', 'Equipo creado exitosamente.');
        });
    }

    public function create(Request $request)
    {
        $eventId = $request->input('event_id');

        if (!$eventId || !is_numeric($eventId)) {
            abort(404, 'El evento no existe o no es válido.');
        }

        $event = Event::find($eventId);

        if (!$event) {
            abort(404, 'Evento no encontrado.');
        }

        return view('teams.create', compact('event'));
    }

    /**
     * Método para aceptar invitación
     */
    public function acceptInvitation(Team $team, $notificationId = null)
    {
        $user = Auth::user();

        // Marcar notificación como leída
        if ($notificationId) {
            $user->notifications()->where('id', $notificationId)->update(['read_at' => now()]);
        }

        // Confirmar que existe invitación
        $exists = $team->members()
            ->wherePivot('user_id', $user->id)
            ->wherePivot('requested_by_user', false)
            ->wherePivot('is_accepted', false)
            ->exists();

        if (!$exists) {
            return back()->with('error', 'No tienes una invitación pendiente.');
        }

        // Aceptar
        $team->members()->updateExistingPivot($user->id, [
            'is_accepted' => true
        ]);

        return back()->with('success', 'Has aceptado la invitación.');
    }

    /**
     * Método para rechazar invitación
     */
    public function rejectInvitation(Team $team, $notificationId = null)
    {
        $user = Auth::user();

        // Marcar notificación como leída
        if ($notificationId) {
            $user->notifications()->where('id', $notificationId)->update(['read_at' => now()]);
        }

        // Confirmar que existe invitación
        $exists = $team->members()
            ->wherePivot('user_id', $user->id)
            ->wherePivot('requested_by_user', false)
            ->wherePivot('is_accepted', false)
            ->exists();

        if (!$exists) {
            return back()->with('error', 'No tienes una invitación pendiente.');
        }

        // Rechazar (eliminar la invitación)
        $team->members()->detach($user->id);

        return back()->with('success', 'Has rechazado la invitación.');
    }


    /**
     * (OPCIONAL) Método para solicitar unirse a un equipo
     * Si NO lo tenías, podés pegarlo.
     */
    public function requestJoin(Team $team)
    {
        $user = Auth::user();

        // Verificar si ya existe relación
        if ($team->members()->where('user_id', $user->id)->exists()) {
            return back()->with('error', 'Ya existe una relación con este equipo.');
        }

        // SOLICITUD (no invitación)
        $team->members()->attach($user->id, [
            'is_accepted' => false,
            'requested_by_user' => true  // ← El usuario pidió unirse
        ]);

        // Notificar al líder
        $team->leader->notify(new \App\Notifications\TeamJoinRequestNotification($team, $user));

        return back()->with('success', 'Solicitud enviada.');
    }
}
