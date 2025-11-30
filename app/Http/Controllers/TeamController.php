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
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
        // ... validaciones ...
        
        $event = Event::findOrFail($request->query('event_id'));

        // ... más validaciones ...

        // ¡ESTA LÍNEA ES CRUCIAL!
        return view('teams.create', compact('event'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        // 1. Validación Básica
        $request->validate([
            'name' => 'required|string|max:50',
            'event_id' => 'required|exists:events,id',
            'members' => 'array|max:4', // Máximo 4 compañeros (+ el líder = 5)
            'members.*' => 'nullable|email|distinct', // Correos válidos y no repetidos
        ]);

        // 2. Transacción (Todo o Nada)
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

            // 3. Crear el Equipo
            $team = Team::create([
                'name' => $request->name,
                'event_id' => $event->id,
                'leader_id' => Auth::id(),
            ]);

            // 4. Agregar al Líder (Tú) a la tabla de miembros automáticamente
            $team->members()->attach(Auth::id(), ['is_accepted' => true]);

            // 5. Procesar los Integrantes
            // Filtramos el array para quitar campos vacíos (si solo puso 1 correo de 4)
            $memberEmails = array_filter($request->members, fn($value) => !is_null($value) && $value !== '');

            foreach ($memberEmails as $email) {
                // Buscamos al usuario por correo
                $user = User::where('email', $email)->first();

                if (! $user) {
                    // Si un correo no existe, cancelamos TODO.
                    throw ValidationException::withMessages([
                        'members' => "El usuario con correo '$email' no está registrado en la plataforma."
                    ]);
                }

                if ($user->id === Auth::id()) {
                    continue; // Si se puso a sí mismo, lo saltamos (ya se agregó arriba)
                }

                // TODO: Aquí podrías validar si ese usuario ya está en otro equipo, 
                // pero por ahora lo dejaremos pasar para no complicar.

                // Agregamos al miembro
                // 'is_accepted' => false significa que le llegó invitación pero no ha aceptado.
                // Si quieres que entren directo, ponle true.
                $team->members()->attach($user->id, ['is_accepted' => true]);
            }

            return redirect()->route('events.show', $event)
                ->with('success', '¡Equipo registrado correctamente!');
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Team $team)
    {
        //
        
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

    public function join(Team $team)
    {
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
        $team->members()->attach(Auth::id(), ['is_accepted' => true]);

        return back()->with('success', 'Te has unido al equipo ' . $team->name . ' correctamente.');
    }
}
