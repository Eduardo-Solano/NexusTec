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
    public function index(Request $request)
    {
        if (Auth::user()->hasRole('judge')) {
            abort(403, 'Acceso denegado. Los jueces no tienen acceso al directorio de equipos.');
        }

        $events = Event::orderBy('name')->get();

        $query = Team::with(['event', 'members', 'leader', 'advisor']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhereHas('leader', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

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

        $invitedEmails = array_filter($request->members ?? []);

        $maxMembers = $event->max_team_members ?? 5;
        $totalRequested = 1 + count($invitedEmails);

        if ($totalRequested > $maxMembers) {
            throw ValidationException::withMessages([
                'members' => "Este evento permite máximo {$maxMembers} integrantes por equipo (incluyendo al líder). Estás intentando registrar {$totalRequested}."
            ]);
        }

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

            $team = Team::create([
                'name' => $request->name,
                'event_id' => $event->id,
                'leader_id' => Auth::id(),
                'advisor_id' => $request->advisor_id,
                'advisor_status' => 'pending'
            ]);

            $team->members()->attach(Auth::id(), [
                'is_accepted' => true,
                'requested_by_user' => false,
                'role' => $request->leader_role
            ]);

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

                $user->notify(new TeamInvitationNotification($team));
            }

            ActivityLog::log('created', "Equipo '{$team->name}' creado para el evento '{$event->name}'", $team, [
                'event_id' => $event->id,
                'event_name' => $event->name,
                'members_invited' => count($invitedEmails),
            ]);

            return redirect()->route('events.show', $event)
                ->with('success', 'Equipo creado exitosamente.');
        });
    }

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

        if (!$event->allowsTeamRegistration()) {
            return redirect()->route('events.show', $event)
                ->with('error', 'No se pueden crear equipos porque el evento no está en período de inscripciones.');
        }

        return view('teams.create', compact('event'));
    }

    public function edit(Team $team)
    {
        if (Auth::id() !== $team->leader_id && !Auth::user()->hasRole(['admin', 'staff'])) {
            abort(403, 'No tienes permiso para editar este equipo. Solo el líder puede hacerlo.');
        }

        $team->load(['event', 'members', 'leader', 'advisor', 'project']);

        $advisors = User::role('advisor')->orderBy('name')->get();
        $events = Event::whereIn('status', [Event::STATUS_REGISTRATION, Event::STATUS_ACTIVE])->orderBy('name')->get();

        return view('teams.edit', compact('team', 'advisors', 'events'));
    }

    public function update(UpdateTeamRequest $request, Team $team)
    {
        if (Auth::id() !== $team->leader_id && !Auth::user()->hasRole(['admin', 'staff'])) {
            abort(403, 'No tienes permiso para actualizar este equipo.');
        }

        $team->update([
            'name' => $request->name,
        ]);

        return redirect()->route('teams.show', $team)
            ->with('success', 'Equipo actualizado correctamente.');
    }

    public function requestJoin(JoinTeamRequest $request, Team $team)
    {
        $user = Auth::user();

        if (!$team->event->allowsTeamRegistration()) {
            return back()->with('error', 'No se pueden unir al equipo porque el evento no está en período de inscripciones.');
        }

        $existing = $team->members()->where('user_id', $user->id)->first();
        if ($existing) {
            if ($existing->pivot->is_accepted) {
                return back()->with('error', 'Ya estás en este equipo.');
            }

            if ($existing->pivot->requested_by_user) {
                return back()->with('error', 'Ya enviaste una solicitud.');
            }

            return back()->with('error', 'Tienes una invitación pendiente.');
        }

        $team->members()->attach($user->id, [
            'is_accepted' => false,
            'requested_by_user' => true,
            'role' => $request->role
        ]);

        $team->leader->notify(new TeamJoinRequestNotification($team, $user));

        return back()->with('success', 'Solicitud enviada.');
    }

    public function accept(Team $team, User $user, Request $request)
    {
        if (!$team->event->allowsTeamRegistration()) {
            return back()->with('error', 'No se pueden aceptar solicitudes porque el evento no está en período de inscripciones.');
        }

        $maxMembers = $team->event->max_team_members ?? 5;
        $currentAccepted = $team->members()
            ->wherePivot('is_accepted', true)
            ->count();

        $isAlreadyAccepted = $team->members()
            ->where('user_id', $user->id)
            ->wherePivot('is_accepted', true)
            ->exists();

        if (!$isAlreadyAccepted && $currentAccepted >= $maxMembers) {
            return back()->with('error', "No puedes aceptar más integrantes. El equipo ya alcanzó el máximo de {$maxMembers} miembros permitidos.");
        }

        if ($request->notification) {
            Auth::user()
                ->notifications()
                ->where('id', $request->notification)
                ->first()?->delete();
        }

        $team->members()->updateExistingPivot($user->id, [
            'is_accepted' => true
        ]);

        $user->notify(new TeamJoinResponseNotification($team, 'accepted'));

        return back()->with('success', 'Miembro aceptado.');
    }

    public function reject(Team $team, User $user, Request $request)
    {
        if ($request->notification) {
            Auth::user()
                ->notifications()
                ->where('id', $request->notification)
                ->first()?->delete();
        }

        $team->members()->detach($user->id);

        $user->notify(new TeamJoinResponseNotification($team, 'rejected'));

        return back()->with('success', 'Solicitud rechazada.');
    }

    public function checkInvitationEmail(Request $request, Team $team)
    {
        $user = Auth::user();

        if ($team->leader_id !== $user->id) {
            abort(403, 'Solo el líder del equipo puede invitar miembros.');
        }

        if (!$team->event->allowsTeamRegistration()) {
            return back()->with('error', 'El evento no está en período de inscripciones.');
        }

        $data = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $invitedUser = User::where('email', $data['email'])->first();

        if (!$invitedUser) {
            return back()
                ->withErrors([
                    'email' => 'Usuario no registrado.',
                ])
                ->withInput();
        }

        if ($invitedUser->id === $user->id) {
            return back()
                ->withErrors([
                    'email' => 'No puedes invitarte a ti mismo.',
                ])
                ->withInput();
        }

        // Verificar si ya es miembro o tiene algo pendiente
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

        $maxMembers = $team->event->max_team_members ?? 5;
        $currentTotal = $team->members()->count();

        if ($currentTotal >= $maxMembers) {
            return back()
                ->withErrors([
                    'email' => "No puedes invitar más personas. El equipo ya alcanzó el máximo de {$maxMembers} integrantes.",
                ])
                ->withInput();
        }

        $team->members()->attach($invitedUser->id, [
            'is_accepted' => false,
            'requested_by_user' => false,
            'role' => 'Miembro',
        ]);

        $invitedUser->notify(new TeamInvitationNotification($team));

        return back()->with('invite_check_success', "Se ha enviado una invitación a {$invitedUser->email}.");
    }



    /**
     * Aceptar invitación (cuando el usuario actual es el invitado)
     */
    public function acceptInvitation(Team $team, $notification = null)
    {
        $user = Auth::user();

        if (!$team->event->allowsTeamRegistration()) {
            return back()->with('error', 'No se pueden aceptar invitaciones porque el evento no está en período de inscripciones.');
        }

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

        if ($member->pivot->is_accepted) {
            return back()->with('success', 'Ya eres miembro de este equipo.');
        }

        // Aceptar invitación
        $team->members()->updateExistingPivot($user->id, [
            'is_accepted' => true,
        ]);

        return back()->with('success', '¡Te has unido al equipo exitosamente!');
    }


    public function rejectInvitation(Team $team, $notification = null)
    {
        $user = Auth::user();

        if ($notification) {
            $user->notifications()
                ->where('id', $notification)
                ->first()?->delete();
        }

        $member = $team->members()->where('user_id', $user->id)->first();

        if (!$member) {
            return back()->with('error', 'No tienes una invitación pendiente para este equipo.');
        }

        $team->members()->detach($user->id);

        return back()->with('success', 'Invitación rechazada.');
    }

    public function respondAdvisory(Team $team, string $status, Request $request)
    {
        $user = Auth::user();

        if ($status === 'accepted' && !$team->event->allowsTeamRegistration()) {
            return back()->with('error', 'No se pueden aceptar solicitudes de asesoría porque el evento no está en período de inscripciones.');
        }

        if ($team->advisor_id !== $user->id) {
            return back()->with('error', 'No tienes permiso para responder a esta solicitud.');
        }

        if ($team->advisor_status !== 'pending') {
            return back()->with('error', 'Esta solicitud ya fue respondida.');
        }

        if (!in_array($status, ['accepted', 'rejected'])) {
            return back()->with('error', 'Estado inválido.');
        }

        if ($request->notification) {
            $user->notifications()->where('id', $request->notification)->update(['read_at' => now()]);
        }

        if ($status === 'accepted') {
            $team->update(['advisor_status' => 'accepted']);
            return back()->with('success', '¡Has aceptado ser asesor de este equipo!');
        } else {
            $team->update([
                'advisor_id' => null,
                'advisor_status' => null
            ]);
            return back()->with('success', 'Has rechazado la solicitud de asesoría.');
        }
    }

    public function leaveTeam(Team $team)
    {
        $user = Auth::user();

        $member = $team->members()->where('user_id', $user->id)->first();
        if (!$member) {
            return back()->with('error', 'No eres miembro de este equipo.');
        }

        if ($team->leader_id === $user->id) {
            return back()->with('error', 'Como líder, debes transferir el liderazgo a otro miembro antes de abandonar el equipo.');
        }

        $team->members()->detach($user->id);

        ActivityLog::log('left_team', "El usuario '{$user->name}' abandonó el equipo '{$team->name}'", $team, [
            'user_id' => $user->id,
            'user_name' => $user->name,
        ]);

        return redirect()->route('events.show', $team->event)
            ->with('success', 'Has abandonado el equipo correctamente.');
    }

    public function kickMember(Team $team, User $user)
    {
        $leader = Auth::user();

        if ($team->leader_id !== $leader->id) {
            return back()->with('error', 'Solo el líder del equipo puede expulsar miembros.');
        }

        if ($user->id === $leader->id) {
            return back()->with('error', 'No puedes expulsarte a ti mismo.');
        }

        $member = $team->members()->where('user_id', $user->id)->first();
        if (!$member) {
            return back()->with('error', 'Este usuario no es miembro del equipo.');
        }

        if ($team->event->isClosed()) {
            return back()->with('error', 'No se pueden expulsar miembros porque el evento está cerrado.');
        }

        $team->members()->detach($user->id);

        ActivityLog::log('kicked_member', "El líder '{$leader->name}' expulsó a '{$user->name}' del equipo '{$team->name}'", $team, [
            'kicked_user_id' => $user->id,
            'kicked_user_name' => $user->name,
        ]);

        return back()->with('success', "'{$user->name}' ha sido expulsado del equipo.");
    }

    public function transferLeadership(Team $team, User $user)
    {
        $currentLeader = Auth::user();

        if ($team->leader_id !== $currentLeader->id) {
            return back()->with('error', 'Solo el líder actual puede transferir el liderazgo.');
        }

        if ($user->id === $currentLeader->id) {
            return back()->with('error', 'Ya eres el líder del equipo.');
        }

        $member = $team->members()
            ->where('user_id', $user->id)
            ->wherePivot('is_accepted', true)
            ->first();

        if (!$member) {
            return back()->with('error', 'Este usuario no es un miembro activo del equipo.');
        }

        if ($team->event->isClosed()) {
            return back()->with('error', 'No se puede transferir liderazgo porque el evento está cerrado.');
        }

        $team->update(['leader_id' => $user->id]);

        ActivityLog::log('transferred_leadership', "'{$currentLeader->name}' transfirió el liderazgo a '{$user->name}' en el equipo '{$team->name}'", $team, [
            'old_leader_id' => $currentLeader->id,
            'old_leader_name' => $currentLeader->name,
            'new_leader_id' => $user->id,
            'new_leader_name' => $user->name,
        ]);

        return back()->with('success', "El liderazgo ha sido transferido a '{$user->name}'.");
    }

    public function cancelInvitation(Team $team, User $user)
    {
        $leader = Auth::user();

        if ($team->leader_id !== $leader->id) {
            return back()->with('error', 'Solo el líder del equipo puede cancelar invitaciones.');
        }

        $invitation = $team->members()
            ->where('user_id', $user->id)
            ->wherePivot('is_accepted', false)
            ->wherePivot('requested_by_user', false)
            ->first();

        if (!$invitation) {
            return back()->with('error', 'No hay invitación pendiente para este usuario.');
        }

        $team->members()->detach($user->id);

        $user->unreadNotifications()
            ->where('type', 'App\Notifications\TeamInvitationNotification')
            ->whereJsonContains('data->team_id', $team->id)
            ->update(['read_at' => now()]);

        return back()->with('success', "La invitación a '{$user->name}' ha sido cancelada.");
    }
}
