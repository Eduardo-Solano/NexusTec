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
        // Solo para Admin/Staff (Seguridad básica)
        if (!Auth::user()->hasAnyRole(['admin', 'staff'])) {
            abort(403);
        }
        $teams = Team::with(['event', 'leader'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

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
        $team->load(['members', 'event', 'project', 'leader']);
        
        return view('teams.show', compact('team'));
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Team $team)
    {
        //
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Team $team)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Team $team)
    {
        //
    }

    public function join(Team $team, Request $request)
    {
        $request->validate([
            'role' => 'required|string'
        ]);
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
}
