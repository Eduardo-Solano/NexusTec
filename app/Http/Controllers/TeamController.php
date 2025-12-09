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
use App\Http\Requests\Team\StoreTeamRequest;
use App\Http\Requests\Team\UpdateTeamRequest;
use App\Http\Requests\Team\JoinTeamRequest;
use App\Notifications\TeamJoinResponseNotification;


class TeamController extends Controller
{
    /**
     * Crear equipo
     */
    public function index(Request $request)
    {
        // ⛔ SEGURIDAD: Los jueces NO pueden ver el listado general de equipos
        if (Auth::user()->hasRole('judge')) {
            abort(403, 'Acceso denegado. Los jueces no tienen acceso al directorio de equipos.');
        }

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



    public function store(StoreTeamRequest $request)
    {
        $event = Event::findOrFail($request->event_id);

        if (!$event->allowsTeamRegistration()) {
            return back()->with('error', 'No se pueden registrar equipos porque el evento no está en período de inscripciones.');
        }

        // Normalizar lista de correos de miembros invitados
        $invitedEmails = array_filter($request->members ?? []);

        // 👉 LÍMITE DE INTEGRANTES (incluye al líder)
        $maxMembers = $event->max_team_members ?? 5; // ajusta el campo si se llama diferente
        $totalRequested = 1 + count($invitedEmails); // 1 líder + N invitados

        if ($totalRequested > $maxMembers) {
            throw ValidationException::withMessages([
                'members' => "Este evento permite máximo {$maxMembers} integrantes por equipo (incluyendo al líder). Estás intentando registrar {$totalRequested}."
            ]);
        }

        // Validar correos
        $correosInvalidos = [];
        foreach ($invitedEmails as $email) {
            if (!User::where('email', $email)->exists()) {
                $correosInvalidos[] = $email;
            }
        }
        if ($correosInvalidos) {
            throw ValidationException::withMessages([
                'members' => 'Correos no válidos: ' . implode(', ', $correosInvalidos)
            ]);
        }

        return DB::transaction(function () use ($request, $event, $invitedEmails) {

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
            foreach ($invitedEmails as $index => $email) {
                $user = User::where('email', $email)->first();
                if (!$user || $user->id === Auth::id()) {
                    continue;
                }

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
                'members_invited' => count($invitedEmails),
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

        $user = Auth::user();

        // Base: relación de miembros con campos del pivot
        $membersQuery = $team->members()->withPivot(['is_accepted', 'role', 'requested_by_user']);

        // Líder, admin y staff pueden ver pendientes
        $canSeePending = $user && (
            $user->id === $team->leader_id ||
            $user->hasRole(['admin', 'staff'])
        );

        // Si NO es líder ni admin/staff → solo miembros aceptados
        if (!$canSeePending) {
            $membersQuery->wherePivot('is_accepted', true);
        }

        $members = $membersQuery->get();

        return view('teams.show', compact('team', 'event', 'members'));
    }



    public function create(Request $request)
    {
        $event = Event::find($request->event_id);
        abort_unless($event, 404);

        // Validar que el evento esté en período de inscripciones
        if (!$event->allowsTeamRegistration()) {
            return redirect()->route('events.show', $event)
                ->with('error', 'No se pueden crear equipos porque el evento no está en período de inscripciones.');
        }

        return view('teams.create', compact('event'));
    }

    /**
     * Formulario de edición de equipo
     */
    public function edit(Team $team)
    {
        // ⛔ SEGURIDAD: Solo el líder del equipo o admin/staff pueden editar
        if (Auth::id() !== $team->leader_id && !Auth::user()->hasRole(['admin', 'staff'])) {
            abort(403, 'No tienes permiso para editar este equipo. Solo el líder puede hacerlo.');
        }

        $team->load(['event', 'members', 'leader', 'advisor', 'project']);

        // Obtener asesores disponibles (docentes)
        $advisors = User::role('advisor')->orderBy('name')->get();

        // Obtener eventos en período de inscripción o activos
        $events = Event::whereIn('status', [Event::STATUS_REGISTRATION, Event::STATUS_ACTIVE])->orderBy('name')->get();

        return view('teams.edit', compact('team', 'advisors', 'events'));
    }

    /**
     * Actualizar equipo
     */
    public function update(UpdateTeamRequest $request, Team $team)
    {
        // ⛔ SEGURIDAD: Solo el líder del equipo o admin/staff pueden editar
        if (Auth::id() !== $team->leader_id && !Auth::user()->hasRole(['admin', 'staff'])) {
            abort(403, 'No tienes permiso para actualizar este equipo.');
        }

        $team->update([
            'name' => $request->name,
        ]);

        return redirect()->route('teams.show', $team)
            ->with('success', 'Equipo actualizado correctamente.');
    }

    /**
     * Enviar solicitud para UNIRSE a un equipo
     */
    public function requestJoin(JoinTeamRequest $request, Team $team)
    {
        $user = Auth::user();

        // ⛔ Validar que el evento esté en período de inscripciones
        if (!$team->event->allowsTeamRegistration()) {
            return back()->with('error', 'No se pueden unir al equipo porque el evento no está en período de inscripciones.');
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
        // ⛔ Validar que el evento esté en período de inscripciones
        if (!$team->event->allowsTeamRegistration()) {
            return back()->with('error', 'No se pueden aceptar solicitudes porque el evento no está en período de inscripciones.');
        }

        // 👉 Validar límite de integrantes ANTES de aceptar
        $maxMembers = $team->event->max_team_members ?? 5; // ajusta el campo si se llama distinto
        // Contar solo miembros ya aceptados
        $currentAccepted = $team->members()
            ->wherePivot('is_accepted', true)
            ->count();

        // Verificar si este usuario ya está aceptado (por si acaso)
        $isAlreadyAccepted = $team->members()
            ->where('user_id', $user->id)
            ->wherePivot('is_accepted', true)
            ->exists();

        // Si todavía no está aceptado y ya estamos al tope, NO dejar aceptar
        if (!$isAlreadyAccepted && $currentAccepted >= $maxMembers) {
            return back()->with('error', "No puedes aceptar más integrantes. El equipo ya alcanzó el máximo de {$maxMembers} miembros permitidos.");
        }

        // Borrar la notificación del líder (si viene en el request)
        if ($request->notification) {
            Auth::user()
                ->notifications()
                ->where('id', $request->notification)
                ->first()?->delete();
        }

        // Marcar como aceptado en el pivot
        $team->members()->updateExistingPivot($user->id, [
            'is_accepted' => true
        ]);

        // 🔔 Notificar al usuario solicitante que fue ACEPTADO
        $user->notify(new TeamJoinResponseNotification($team, 'accepted'));

        return back()->with('success', 'Miembro aceptado.');
    }




    /**
     * Rechazar solicitud o invitación
     */
    public function reject(Team $team, User $user, Request $request)
    {
        // Borrar la notificación del líder
        if ($request->notification) {
            Auth::user()
                ->notifications()
                ->where('id', $request->notification)
                ->first()?->delete();
        }

        // Quitar al usuario de la tabla pivot (no se une)
        $team->members()->detach($user->id);

        // 🔔 Notificar al usuario solicitante que fue RECHAZADO
        $user->notify(new TeamJoinResponseNotification($team, 'rejected'));

        return back()->with('success', 'Solicitud rechazada.');
    }

    /**
     * Validar si el correo ingresado existe en el sistema (solo líder).
     */
    /**
     * Validar correo e INVITAR al usuario si existe (solo líder).
     */
    public function checkInvitationEmail(Request $request, Team $team)
    {
        $user = Auth::user();

        // Solo el líder puede usar este cuadro
        if ($team->leader_id !== $user->id) {
            abort(403, 'Solo el líder del equipo puede invitar miembros.');
        }

        // Validar que el evento permita inscripciones
        if (!$team->event->allowsTeamRegistration()) {
            return back()->with('error', 'El evento no está en período de inscripciones.');
        }

        // Validar solo formato de correo (NO existencia todavía)
        $data = $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Buscar usuario por correo
        $invitedUser = User::where('email', $data['email'])->first();

        // Si no existe el usuario => mensaje "Usuario no registrado"
        if (!$invitedUser) {
            return back()
                ->withErrors([
                    'email' => 'Usuario no registrado.',
                ])
                ->withInput();
        }

        // Evitar invitarse a sí mismo
        if ($invitedUser->id === $user->id) {
            return back()
                ->withErrors([
                    'email' => 'No puedes invitarte a ti mismo.',
                ])
                ->withInput();
        }

        // 🔎 Validar si ya está en ALGÚN equipo de este mismo evento
        $alreadyInEventTeam = DB::table('team_user')
            ->join('teams', 'team_user.team_id', '=', 'teams.id')
            ->where('teams.event_id', $team->event_id)
            ->where('team_user.user_id', $invitedUser->id)
            ->exists();

        if ($alreadyInEventTeam) {
            return back()
                ->withErrors([
                    'email' => 'Este usuario ya pertenece a un equipo de este mismo evento.',
                ])
                ->withInput();
        }

        // Verificar si ya es miembro o tiene algo pendiente en ESTE equipo
        $existing = $team->members()->where('user_id', $invitedUser->id)->first();

        if ($existing) {
            if ($existing->pivot->is_accepted) {
                return back()
                    ->withErrors([
                        'email' => 'Este usuario ya es miembro del equipo.',
                    ])
                    ->withInput();
            }

            if ($existing->pivot->requested_by_user) {
                return back()
                    ->withErrors([
                        'email' => 'Este usuario ya envió una solicitud para unirse. Revísala en la ficha del equipo.',
                    ])
                    ->withInput();
            }

            return back()
                ->withErrors([
                    'email' => 'Ya hay una invitación pendiente para este usuario.',
                ])
                ->withInput();
        }

        // Límite de integrantes (miembros + invitaciones pendientes)
        $maxMembers = $team->event->max_team_members ?? 5;
        $currentTotal = $team->members()->count();

        if ($currentTotal >= $maxMembers) {
            return back()
                ->withErrors([
                    'email' => "No puedes invitar más personas. El equipo ya alcanzó el máximo de {$maxMembers} integrantes.",
                ])
                ->withInput();
        }

        // Crear invitación en la tabla pivot (igual que en store)
        $team->members()->attach($invitedUser->id, [
            'is_accepted' => false,
            'requested_by_user' => false,
            'role' => 'Miembro',
        ]);

        // Notificación dentro del sistema (como cuando se crea el equipo)
        $invitedUser->notify(new TeamInvitationNotification($team));

        // Mensaje de éxito (se muestra en el cuadro)
        return back()->with('invite_check_success', "Se ha enviado una invitación a {$invitedUser->email}.");
    }




    /**
     * Aceptar invitación (cuando el usuario actual es el invitado)
     */
    public function acceptInvitation(Team $team, $notification = null)
    {
        $user = Auth::user();

        // ⛔ Validar que el evento esté en período de inscripciones
        if (!$team->event->allowsTeamRegistration()) {
            return back()->with('error', 'No se pueden aceptar invitaciones porque el evento no está en período de inscripciones.');
        }

        // 🔎 Verificar si el usuario ya es miembro ACEPTADO de OTRO equipo del mismo evento
        $alreadyInOtherTeam = DB::table('team_user')
            ->join('teams', 'team_user.team_id', '=', 'teams.id')
            ->where('teams.event_id', $team->event_id)
            ->where('team_user.user_id', $user->id)
            ->where('team_user.team_id', '!=', $team->id)
            ->where('team_user.is_accepted', true)
            ->exists();

        if ($alreadyInOtherTeam) {
            return back()->with('error', 'Ya perteneces a otro equipo de este mismo evento. No puedes aceptar esta invitación.');
        }

        // 🔒 Validar límite de integrantes (solo miembros aceptados)
        $maxMembers = $team->event->max_team_members ?? 5;
        $currentAccepted = $team->members()
            ->wherePivot('is_accepted', true)
            ->count();

        if ($currentAccepted >= $maxMembers) {
            return back()->with('error', "No puedes unirte a este equipo. Ya alcanzó el máximo de {$maxMembers} integrantes.");
        }

        // Marcar notificación como leída (si viene el id)
        if ($notification) {
            $user->notifications()
                ->where('id', $notification)
                ->first()?->delete();
        }

        // Verificar que el usuario TIENE una invitación pendiente en este equipo
        $member = $team->members()->where('user_id', $user->id)->first();

        if (!$member) {
            return back()->with('error', 'No tienes una invitación pendiente para este equipo.');
        }

        if ($member->pivot->is_accepted) {
            return back()->with('success', 'Ya eres miembro de este equipo.');
        }

        // ✅ Aceptar invitación: aquí es donde REALMENTE entra al equipo
        $team->members()->updateExistingPivot($user->id, [
            'is_accepted' => true,
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
            $user->notifications()
                ->where('id', $notification)
                ->first()?->delete();
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

        // ⛔ Validar que el evento permita inscripciones (solo para aceptar)
        if ($status === 'accepted' && !$team->event->allowsTeamRegistration()) {
            return back()->with('error', 'No se pueden aceptar solicitudes de asesoría porque el evento no está en período de inscripciones.');
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

    /**
     * Abandonar equipo (miembro se sale voluntariamente)
     */
    public function leaveTeam(Team $team)
    {
        $user = Auth::user();

        // Verificar que el usuario es miembro del equipo
        $member = $team->members()->where('user_id', $user->id)->first();
        if (!$member) {
            return back()->with('error', 'No eres miembro de este equipo.');
        }

        // El líder no puede abandonar (debe transferir liderazgo primero)
        if ($team->leader_id === $user->id) {
            return back()->with('error', 'Como líder, debes transferir el liderazgo a otro miembro antes de abandonar el equipo.');
        }

        // Remover del equipo
        $team->members()->detach($user->id);

        // Registrar actividad
        ActivityLog::log('left_team', "El usuario '{$user->name}' abandonó el equipo '{$team->name}'", $team, [
            'user_id' => $user->id,
            'user_name' => $user->name,
        ]);

        return redirect()->route('events.show', $team->event)
            ->with('success', 'Has abandonado el equipo correctamente.');
    }

    /**
     * Expulsar miembro del equipo (solo líder)
     */
    public function kickMember(Team $team, User $user)
    {
        $leader = Auth::user();

        // Solo el líder puede expulsar
        if ($team->leader_id !== $leader->id) {
            return back()->with('error', 'Solo el líder del equipo puede expulsar miembros.');
        }

        // No se puede expulsar a sí mismo
        if ($user->id === $leader->id) {
            return back()->with('error', 'No puedes expulsarte a ti mismo.');
        }

        // Verificar que el usuario es miembro del equipo
        $member = $team->members()->where('user_id', $user->id)->first();
        if (!$member) {
            return back()->with('error', 'Este usuario no es miembro del equipo.');
        }

        // ⛔ Validar que el evento esté abierto
        if ($team->event->isClosed()) {
            return back()->with('error', 'No se pueden expulsar miembros porque el evento está cerrado.');
        }

        // Remover del equipo
        $team->members()->detach($user->id);

        // Registrar actividad
        ActivityLog::log('kicked_member', "El líder '{$leader->name}' expulsó a '{$user->name}' del equipo '{$team->name}'", $team, [
            'kicked_user_id' => $user->id,
            'kicked_user_name' => $user->name,
        ]);

        return back()->with('success', "'{$user->name}' ha sido expulsado del equipo.");
    }

    /**
     * Transferir liderazgo a otro miembro (solo líder)
     */
    public function transferLeadership(Team $team, User $user)
    {
        $currentLeader = Auth::user();

        // Solo el líder puede transferir
        if ($team->leader_id !== $currentLeader->id) {
            return back()->with('error', 'Solo el líder actual puede transferir el liderazgo.');
        }

        // No se puede transferir a sí mismo
        if ($user->id === $currentLeader->id) {
            return back()->with('error', 'Ya eres el líder del equipo.');
        }

        // Verificar que el nuevo líder es miembro aceptado del equipo
        $member = $team->members()
            ->where('user_id', $user->id)
            ->wherePivot('is_accepted', true)
            ->first();

        if (!$member) {
            return back()->with('error', 'Este usuario no es un miembro activo del equipo.');
        }

        // ⛔ Validar que el evento esté abierto
        if ($team->event->isClosed()) {
            return back()->with('error', 'No se puede transferir liderazgo porque el evento está cerrado.');
        }

        // Transferir liderazgo
        $team->update(['leader_id' => $user->id]);

        // Registrar actividad
        ActivityLog::log('transferred_leadership', "'{$currentLeader->name}' transfirió el liderazgo a '{$user->name}' en el equipo '{$team->name}'", $team, [
            'old_leader_id' => $currentLeader->id,
            'old_leader_name' => $currentLeader->name,
            'new_leader_id' => $user->id,
            'new_leader_name' => $user->name,
        ]);

        return back()->with('success', "El liderazgo ha sido transferido a '{$user->name}'.");
    }

    /**
     * Cancelar invitación pendiente (solo líder)
     */
    public function cancelInvitation(Team $team, User $user)
    {
        $leader = Auth::user();

        // Solo el líder puede cancelar invitaciones
        if ($team->leader_id !== $leader->id) {
            return back()->with('error', 'Solo el líder del equipo puede cancelar invitaciones.');
        }

        // Verificar que existe una invitación pendiente (no aceptada y no solicitada por el usuario)
        $invitation = $team->members()
            ->where('user_id', $user->id)
            ->wherePivot('is_accepted', false)
            ->wherePivot('requested_by_user', false)
            ->first();

        if (!$invitation) {
            return back()->with('error', 'No hay invitación pendiente para este usuario.');
        }

        // Remover la invitación
        $team->members()->detach($user->id);

        // Marcar notificaciones relacionadas como leídas
        $user->unreadNotifications()
            ->where('type', 'App\Notifications\TeamInvitationNotification')
            ->whereJsonContains('data->team_id', $team->id)
            ->update(['read_at' => now()]);

        return back()->with('success', "La invitación a '{$user->name}' ha sido cancelada.");
    }

}
