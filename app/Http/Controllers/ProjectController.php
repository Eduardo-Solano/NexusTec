<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Team;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function __construct()
    {
        // Todos los autenticados pueden ver proyectos
        $this->middleware('permission:projects.view')->only(['index', 'show']);
        // Solo estudiantes (líder) pueden crear/entregar proyectos
        $this->middleware('permission:projects.deliver')->only(['create', 'store']);
        // El líder o admin/staff pueden editar
        $this->middleware('permission:projects.edit|projects.deliver')->only(['edit', 'update']);
        // Solo admin/staff pueden eliminar y gestionar jueces
        $this->middleware('permission:projects.delete')->only(['destroy']);
        $this->middleware('permission:projects.edit')->only(['assignJudge', 'removeJudge']);
    }

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
            'repository_url' => 'required|url',
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
        $project = Project::create([
            'name' => $request->name,
            'description' => $request->description,
            'repository_url' => $request->repository_url,
            'team_id' => $team->id,
        ]);

        // Registrar actividad
        ActivityLog::log('submitted', "Proyecto '{$project->name}' entregado por el equipo '{$team->name}'", $project, [
            'team_id' => $team->id,
            'team_name' => $team->name,
            'event_id' => $team->event_id,
        ]);

        // Redirigir al evento con éxito
        return redirect()->route('events.show', $team->event_id)
            ->with('success', '¡Proyecto entregado exitosamente! 🚀');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        // Cargar relaciones para mostrar info del equipo, asesor y jueces asignados
        $project->load(['team.members', 'team.leader', 'team.event', 'team.advisor', 'judges.judgeProfile', 'evaluations']);
        
        // Obtener jueces disponibles para asignar (que no estén ya asignados a este proyecto)
        $availableJudges = [];
        if (Auth::user() && Auth::user()->can('projects.edit')) {
            $assignedJudgeIds = $project->judges->pluck('id')->toArray();
            $availableJudges = User::role('judge')
                ->whereNotIn('id', $assignedJudgeIds)
                ->with('judgeProfile.specialty')
                ->get();
        }
        
        return view('projects.show', compact('project', 'availableJudges'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        // Cargar relaciones necesarias
        $project->load(['team.leader', 'team.event', 'evaluations']);
        
        // Verificar permisos: Solo líder del equipo o admin/staff
        $user = Auth::user();
        $isLeader = $project->team->leader_id === $user->id;
        $isAdminOrStaff = $user->hasAnyRole(['admin', 'staff']);
        
        if (!$isLeader && !$isAdminOrStaff) {
            abort(403, 'No tienes permiso para editar este proyecto.');
        }
        
        // Verificar integridad: NO permitir editar si ya tiene evaluaciones
        if ($project->evaluations()->exists()) {
            return redirect()->route('projects.show', $project)
                ->with('error', 'No se puede editar un proyecto que ya ha sido evaluado. Esto protege la integridad de las calificaciones.');
        }
        
        return view('projects.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        // Verificar permisos: Solo líder del equipo o admin/staff
        $user = Auth::user();
        $isLeader = $project->team->leader_id === $user->id;
        $isAdminOrStaff = $user->hasAnyRole(['admin', 'staff']);
        
        if (!$isLeader && !$isAdminOrStaff) {
            abort(403, 'No tienes permiso para editar este proyecto.');
        }
        
        // Verificar integridad: NO permitir editar si ya tiene evaluaciones
        if ($project->evaluations()->exists()) {
            return redirect()->route('projects.show', $project)
                ->with('error', 'No se puede editar un proyecto que ya ha sido evaluado.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'required|string|max:1000',
            'repository_url' => 'required|url',
        ]);
        
        $project->update($validated);
        
        return redirect()->route('projects.show', $project)
            ->with('success', 'Proyecto actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        // Verificar permisos: Solo líder del equipo o admin/staff
        $user = Auth::user();
        $isLeader = $project->team->leader_id === $user->id;
        $isAdminOrStaff = $user->hasAnyRole(['admin', 'staff']);
        
        if (!$isLeader && !$isAdminOrStaff) {
            abort(403, 'No tienes permiso para eliminar este proyecto.');
        }
        
        // Verificar integridad: NO permitir eliminar si tiene evaluaciones
        if ($project->evaluations()->exists()) {
            return back()->with('error', 'No se puede eliminar un proyecto que ya ha sido evaluado. Esto protege la integridad de los datos históricos.');
        }
        
        // Verificar integridad: NO permitir eliminar si el equipo tiene premios
        if ($project->team->awards()->exists()) {
            return back()->with('error', 'No se puede eliminar un proyecto cuyo equipo ha recibido premios.');
        }
        
        $eventId = $project->team->event_id;
        $projectName = $project->name;
        
        // Primero remover jueces asignados (limpieza de tabla pivot)
        $project->judges()->detach();
        
        // Eliminar el proyecto
        $project->delete();
        
        return redirect()->route('events.show', $eventId)
            ->with('success', "Proyecto \"{$projectName}\" eliminado correctamente.");
    }

    /**
     * Asignar un juez a un proyecto
     */
    public function assignJudge(Request $request, Project $project)
    {
        // Verificar permisos
        if (!Auth::user()->can('projects.edit')) {
            abort(403);
        }

        $request->validate([
            'judge_id' => 'required|exists:users,id'
        ]);

        // Verificar que el usuario sea juez
        $judge = User::findOrFail($request->judge_id);
        if (!$judge->hasRole('judge')) {
            return back()->with('error', 'El usuario seleccionado no es un juez.');
        }

        // Verificar que no esté ya asignado
        if ($project->judges()->where('judge_id', $judge->id)->exists()) {
            return back()->with('error', 'Este juez ya está asignado al proyecto.');
        }

        // Asignar juez
        $project->judges()->attach($judge->id, [
            'assigned_at' => now(),
            'is_completed' => false
        ]);

        return back()->with('success', "Juez {$judge->name} asignado correctamente.");
    }

    /**
     * Remover un juez de un proyecto
     */
    public function removeJudge(Project $project, User $judge)
    {
        // Verificar permisos
        if (!Auth::user()->can('projects.edit')) {
            abort(403);
        }

        // Verificar si ya evaluó (no permitir remover si ya hay evaluaciones)
        $hasEvaluations = $project->evaluations()->where('judge_id', $judge->id)->exists();
        if ($hasEvaluations) {
            return back()->with('error', 'No se puede remover un juez que ya ha evaluado el proyecto.');
        }

        // Remover asignación
        $project->judges()->detach($judge->id);

        return back()->with('success', "Juez {$judge->name} removido del proyecto.");
    }
}
