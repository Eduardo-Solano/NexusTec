<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
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
        // 1. Validar que nos pasen el ID del equipo
        if (!$request->has('team_id')) {
            return back()->with('error', 'Identificador de equipo faltante.');
        }

        $team = Team::findOrFail($request->query('team_id'));

        // 2. Seguridad: Solo el LÍDER del equipo puede subir el proyecto
        if ($team->leader_id !== Auth::id()) {
            abort(403, 'Solo el líder del equipo puede entregar el proyecto.');
        }

        // 3. Seguridad: Si ya entregaron, redirigir a ver el proyecto (evitar duplicados)
        if ($team->project) {
            return redirect()->route('projects.show', $team->project);
        }

        return view('projects.create', compact('team'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'team_id' => 'required|exists:teams,id',
            'name' => 'required|string|max:100',
            'description' => 'required|string|max:1000',
            'repository_url' => 'required|url', // Validamos que sea un link real
            'advisor_id' => 'required|exists:users,id',
        ]);

        $team = Team::findOrFail($request->team_id);

        // Seguridad: Solo el LÍDER del equipo puede entregar el proyecto
        if ($team->leader_id !== Auth::id()) {
            abort(403, 'Solo el líder del equipo puede entregar el proyecto.');
        }

        // Seguridad: Si ya entregaron, evitar duplicados
        if ($team->project) {
            return redirect()->route('projects.show', $team->project)
                ->with('info', 'El proyecto ya fue entregado anteriormente.');
        }

        // Crear el proyecto
        Project::create([
            'name' => $request->name,
            'description' => $request->description,
            'repository_url' => $request->repository_url,
            'team_id' => $team->id,
        ]);

        // Actualizar el equipo con el asesor seleccionado
        $team->update([
            'advisor_id' => $request->advisor_id,
            'advisor_status' => 'pending',
        ]);

        // Redirigir al evento con éxito
        return redirect()->route('events.show', $team->event_id)
            ->with('success', '¡Proyecto entregado exitosamente! Se ha enviado la solicitud de asesoría. 🚀');
    
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        // Cargar relaciones para mostrar info del equipo
        $project->load('team.members', 'team.leader');
        
        return view('projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        //
    }
}
