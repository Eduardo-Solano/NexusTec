<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Admin/Staff ven todos los equipos
        if (Auth::user()->can('teams.view')) {
            $teams = Team::with(['event', 'leader'])
                ->orderBy('created_at', 'desc')
                ->paginate(12);
        } else {
            // Estudiantes solo ven equipos donde son miembros
            $teams = Team::with(['event', 'leader'])
                ->whereHas('members', function($q) {
                    $q->where('user_id', Auth::id());
                })
                ->orderBy('created_at', 'desc')
                ->paginate(12);
        }

        return view('teams.index', compact('teams'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // 1. Validar ID del evento
        if (! $request->has('event_id')) {
            return redirect()->route('events.index')->with('error', 'Falta el evento.');
        }
        
        $event = Event::findOrFail($request->query('event_id'));

        // 2. Validar si está activo
        if (! $event->is_active) {
            return redirect()->route('events.show', $event)->with('error', 'Evento cerrado.');
        }

        // --- 3. NUEVA VALIDACIÓN DE INTEGRIDAD ---
        // Verificar si el usuario YA pertenece a un equipo en este evento
        $alreadyInTeam = $event->teams()->whereHas('members', function($q) {
            $q->where('user_id', Auth::id());
        })->exists();

        if ($alreadyInTeam) {
            // Si ya tiene equipo, lo pateamos fuera con error
            return redirect()->route('events.show', $event)
                ->with('error', 'Error: Ya estás registrado en un equipo para este evento.');
        }

        // ¡ESTA LÍNEA ES CRUCIAL!
        return view('teams.create', compact('event'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validación Actualizada
        $request->validate([
            'name' => 'required|string|max:50',
            'event_id' => 'required|exists:events,id',
            'leader_role' => 'required|string', // Nuevo: Rol del líder
            'members' => 'array|max:4',
            'member_roles' => 'array', // Nuevo: Roles de los miembros
            'members.*' => 'nullable|email|distinct',
            'advisor_id' => 'required|exists:users,id', // Nuevo: Asesor
        ]);

        return DB::transaction(function () use ($request) {
            $event = Event::findOrFail($request->event_id);

            // Regla de Negocio: ¿El usuario ya es líder o miembro de otro equipo en este evento?
            // (Esta consulta verifica si el ID del usuario ya está en la tabla pivote para este evento)
            $existingEntry = DB::table('team_user')
                ->join('teams', 'team_user.team_id', '=', 'teams.id')
                ->where('teams.event_id', $event->id)
                ->where('team_user.user_id', Auth::id())
                ->exists();

            if ($existingEntry) {
                throw ValidationException::withMessages([
                    'event_id' => 'Ya estás registrado en un equipo para este evento.'
                ]);
            }

            // Crear Equipo
            $team = Team::create([
                'name' => $request->name,
                'event_id' => $event->id,
                'leader_id' => Auth::id(),
                'advisor_id' => $request->advisor_id, // <--- GUARDAMOS AQUÍ
                'advisor_status' => 'pending'
            ]);

            // Guardar al LIDER con su Rol
            $team->members()->attach(Auth::id(), [
                'is_accepted' => true,
                'role' => $request->leader_role // <--- AQUÍ SE GUARDA
            ]);

            // Procesar Miembros
            $memberEmails = $request->members ?? [];
            $memberRoles = $request->member_roles ?? [];

            foreach ($memberEmails as $index => $email) {
                if (empty($email)) continue;

                $user = User::where('email', $email)->first();
                if (!$user) {
                    throw ValidationException::withMessages(['members' => "El correo $email no existe."]);
                }

                if ($user->id === Auth::id()) continue;

                // Guardar Miembro con su Rol correspondiente (por índice)
                $role = $memberRoles[$index] ?? 'Colaborador'; // Default
                
                $team->members()->attach($user->id, [
                    'is_accepted' => true,
                    'role' => $role // <--- AQUÍ SE GUARDA
                ]);
            }

            return redirect()->route('events.show', $event)->with('success', 'Equipo registrado con roles.');
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Team $team)
    {
        // Cargar relaciones necesarias para la vista
        $team->load(['members', 'event', 'project', 'leader', 'advisor']);
        
        return view('teams.show', compact('team'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Team $team)
    {
        // Admin/Staff pueden editar cualquier equipo, el líder puede editar su equipo
        $isLeader = Auth::id() === $team->leader_id;
        
        if (!Auth::user()->can('teams.edit') && !$isLeader) {
            abort(403);
        }

        $events = Event::orderBy('name')->get();
        return view('teams.edit', compact('team', 'events', 'isLeader'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Team $team)
    {
        // Admin/Staff pueden actualizar cualquier equipo, el líder puede actualizar su equipo
        $isLeader = Auth::id() === $team->leader_id;
        
        if (!Auth::user()->can('teams.edit') && !$isLeader) {
            abort(403);
        }

        // El líder solo puede cambiar nombre y líder, no el evento
        if ($isLeader && !Auth::user()->can('teams.edit')) {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'leader_id' => 'required|exists:users,id',
            ]);
        } else {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'event_id' => 'required|exists:events,id',
                'leader_id' => 'required|exists:users,id',
            ]);
        }

        // Verificar que el líder sea un miembro del equipo
        if (!$team->members()->where('user_id', $validated['leader_id'])->exists()) {
            return back()->withErrors(['leader_id' => 'El líder seleccionado debe ser miembro del equipo.']);
        }

        $team->update($validated);

        return redirect()->route('teams.show', $team)->with('success', 'Equipo actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Team $team)
    {
        // Seguridad: Solo admin o el líder pueden borrar
        if (!Auth::user()->hasRole('admin') && Auth::id() !== $team->leader_id) {
            abort(403);
        }
        $team->delete(); // El cascadeOnDelete de la BD borrará miembros y proyectos
        return back()->with('success', 'Equipo eliminado correctamente.');
    }

    public function join(Team $team, Request $request)
    {
        $request->validate([
            'role' => 'required|string'
        ]);
        
        // 0. Validar si el equipo ya entregó proyecto (no se pueden unir más miembros)
        if ($team->project) {
            return back()->with('error', 'Este equipo ya entregó su proyecto. No se pueden agregar más integrantes.');
        }
        
        // 1. Validar si el equipo ya está lleno (Max 5)
        if ($team->members()->count() >= 5) {
            return back()->with('error', 'Este equipo ya está lleno.');
        }

        // 2. Validar si el usuario YA está inscrito en ESTE evento (en cualquier equipo)
        $alreadyRegistered = DB::table('team_user')
            ->join('teams', 'team_user.team_id', '=', 'teams.id')
            ->where('teams.event_id', $team->event_id)
            ->where('team_user.user_id', Auth::id())
            ->exists();

        if ($alreadyRegistered) {
            return back()->with('error', 'Ya perteneces a un equipo en este evento.');
        }

        // 3. Unir al usuario
        // 'is_accepted' => true para que entre directo. 
        // Si quisieras aprobación del líder, pondrías false.
        $team->members()->attach(Auth::id(), [
            'is_accepted' => false,
            'role' => $request->role // <--- Guardamos el rol elegido
        ]);

        return back()->with('success', 'Solicitud enviada. Espera a que el líder te acepte.');
    }

    public function acceptMember(Team $team, User $user)
    {
        // Seguridad: Solo el líder puede aceptar
        if (Auth::id() !== $team->leader_id) {
            abort(403, 'No eres el líder de este equipo.');
        }

        // Actualizar pivote
        $team->members()->updateExistingPivot($user->id, ['is_accepted' => true]);

        return back()->with('success', 'Miembro aceptado en el equipo.');
    }

    public function rejectMember(Team $team, User $user)
    {
        // Seguridad: Solo el líder puede rechazar
        if (Auth::id() !== $team->leader_id) {
            abort(403);
        }

        // Borrar relación (pivote)
        $team->members()->detach($user->id);

        return back()->with('success', 'Solicitud rechazada.');
    }

    public function respondAdvisory(Request $request, Team $team, $status)
    {
        if (Auth::id() !== $team->advisor_id) abort(403);
        
        // Validar que el status sea válido
        if (!in_array($status, ['accepted', 'rejected', 'pending'])) {
            return back()->with('error', 'Estado de asesoría no válido.');
        }
        
        $team->update(['advisor_status' => $status]);
        
        $message = $status === 'accepted' 
            ? '¡Has aceptado ser asesor de este equipo!' 
            : 'Has rechazado la solicitud de asesoría.';
            
        return back()->with('success', $message);
    }
}
