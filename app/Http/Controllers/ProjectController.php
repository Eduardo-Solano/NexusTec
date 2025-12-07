<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Team;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // ⛔ SEGURIDAD: Los jueces NO pueden ver el listado general de proyectos
        if (Auth::user()->hasRole('judge')) {
            abort(403, 'Acceso denegado. Los jueces solo pueden acceder a sus proyectos asignados desde el Dashboard.');
        }

        // Obtener lista de eventos para el filtro
        $events = \App\Models\Event::orderBy('name')->get();

        $query = Project::with(['team.event', 'team.leader', 'team.advisor', 'judges']);

        // Filtro por búsqueda (nombre del proyecto o equipo)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('team', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filtro por evento
        if ($request->filled('event_id')) {
            $query->whereHas('team', function ($q) use ($request) {
                $q->where('event_id', $request->event_id);
            });
        }

        $projects = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('projects.index', compact('projects', 'events'));
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

        // ⛔ Validar que el evento permita acciones de proyecto (estado activo)
        if (!$team->event->allowsProjectActions()) {
            return back()->with('error', 'No se pueden entregar proyectos porque el evento no está en curso.');
        }

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
            'documentation' => 'nullable|file|mimes:pdf|max:10240', // Máx 10MB
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120', // Máx 5MB
            'video_url' => 'nullable|url',
        ]);

        $team = Team::findOrFail($request->team_id);

        // ⛔ Validar que el evento permita acciones de proyecto (estado activo)
        if (!$team->event->allowsProjectActions()) {
            return back()->with('error', 'No se pueden entregar proyectos porque el evento no está en curso.');
        }

        // Seguridad: Solo el LÍDER del equipo puede entregar el proyecto
        if ($team->leader_id !== Auth::id()) {
            abort(403, 'Solo el líder del equipo puede entregar el proyecto.');
        }

        // Seguridad: Si ya entregaron, evitar duplicados
        if ($team->project) {
            return redirect()->route('projects.show', $team->project)
                ->with('info', 'El proyecto ya fue entregado anteriormente.');
        }

        // Procesar archivos subidos
        $documentationPath = null;
        $imagePath = null;

        if ($request->hasFile('documentation')) {
            $documentationPath = $request->file('documentation')->store(
                "projects/{$team->event_id}/{$team->id}/docs", 
                'public'
            );
        }

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store(
                "projects/{$team->event_id}/{$team->id}/images", 
                'public'
            );
        }

        // Crear el proyecto
        $project = Project::create([
            'name' => $request->name,
            'description' => $request->description,
            'repository_url' => $request->repository_url,
            'team_id' => $team->id,
            'documentation_path' => $documentationPath,
            'image_path' => $imagePath,
            'video_url' => $request->video_url,
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
        // ⛔ SEGURIDAD: Jueces solo pueden ver proyectos asignados
        if (Auth::user()->hasRole('judge')) {
            $isAssigned = $project->judges()->where('judge_id', Auth::id())->exists();
            if (!$isAssigned) {
                abort(403, 'Acceso denegado. No tienes asignada la evaluación de este proyecto.');
            }
        }

        // Cargar relaciones para mostrar info del equipo, asesor y jueces asignados
        $project->load(['team.members', 'team.leader', 'team.event', 'team.advisor', 'judges.judgeProfile', 'evaluations']);
        
        // Obtener jueces disponibles para asignar (que no estén ya asignados a este proyecto)
        // Y que estén asignados al EVENTO del proyecto
        $availableJudges = [];
        if (Auth::user() && Auth::user()->can('projects.edit')) {
            $assignedJudgeIds = $project->judges->pluck('id')->toArray();
            
            // Obtener IDs de usuarios de los jueces asignados al evento
            // La relación event->judges devuelve JudgeProfile, necesitamos los user_id
            $eventJudgeUserIds = $project->team->event->judges()->pluck('user_id');

            $availableJudges = User::role('judge')
                ->whereIn('id', $eventJudgeUserIds)
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
        
        // ⛔ Validar que el evento permita acciones de proyecto
        if (!$project->team->event->allowsProjectActions()) {
            return redirect()->route('projects.show', $project)
                ->with('error', 'No se puede editar el proyecto porque el evento no está en curso.');
        }
        
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
        // ⛔ Validar que el evento permita acciones de proyecto
        if (!$project->team->event->allowsProjectActions()) {
            return redirect()->route('projects.show', $project)
                ->with('error', 'No se puede editar el proyecto porque el evento no está en curso.');
        }
        
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
            'documentation' => 'nullable|file|mimes:pdf|max:10240', // Máx 10MB
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120', // Máx 5MB
            'video_url' => 'nullable|url',
            'remove_documentation' => 'nullable|boolean',
            'remove_image' => 'nullable|boolean',
        ]);

        // Manejar eliminación de archivos existentes
        if ($request->boolean('remove_documentation') && $project->documentation_path) {
            Storage::disk('public')->delete($project->documentation_path);
            $project->documentation_path = null;
        }

        if ($request->boolean('remove_image') && $project->image_path) {
            Storage::disk('public')->delete($project->image_path);
            $project->image_path = null;
        }

        // Procesar nuevos archivos subidos
        if ($request->hasFile('documentation')) {
            // Eliminar archivo anterior si existe
            if ($project->documentation_path) {
                Storage::disk('public')->delete($project->documentation_path);
            }
            $project->documentation_path = $request->file('documentation')->store(
                "projects/{$project->team->event_id}/{$project->team_id}/docs", 
                'public'
            );
        }

        if ($request->hasFile('image')) {
            // Eliminar archivo anterior si existe
            if ($project->image_path) {
                Storage::disk('public')->delete($project->image_path);
            }
            $project->image_path = $request->file('image')->store(
                "projects/{$project->team->event_id}/{$project->team_id}/images", 
                'public'
            );
        }

        // Actualizar campos básicos
        $project->name = $validated['name'];
        $project->description = $validated['description'];
        $project->repository_url = $validated['repository_url'];
        $project->video_url = $request->video_url;
        $project->save();
        
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

        // Eliminar archivos asociados
        $project->deleteFiles();
        
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

        // ⛔ Validar que el evento permita evaluaciones (estado activo)
        if (!$project->team->event->allowsEvaluations()) {
            return back()->with('error', 'No se pueden asignar jueces porque el evento no está en curso.');
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
