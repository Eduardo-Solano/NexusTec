<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Team;
use App\Models\User;
use App\Models\ActivityLog;
use App\Http\Requests\Project\StoreProjectRequest;
use App\Http\Requests\Project\UpdateProjectRequest;
use App\Http\Requests\Project\AssignJudgeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->hasRole('judge')) {
            abort(403, 'Acceso denegado. Los jueces solo pueden acceder a sus proyectos asignados desde el Dashboard.');
        }

        $events = \App\Models\Event::orderBy('name')->get();

        $query = Project::with(['team.event', 'team.leader', 'team.advisor', 'judges']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('team', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('event_id')) {
            $query->whereHas('team', function ($q) use ($request) {
                $q->where('event_id', $request->event_id);
            });
        }

        $projects = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('projects.index', compact('projects', 'events'));
    }

    public function create(Request $request)
    {
        if (!$request->has('team_id')) {
            return back()->with('error', 'Identificador de equipo faltante.');
        }

        $team = Team::findOrFail($request->query('team_id'));

        if (!$team->event->allowsProjectActions()) {
            return back()->with('error', 'No se pueden entregar proyectos porque el evento no está en curso.');
        }

        if ($team->leader_id !== Auth::id()) {
            abort(403, 'Solo el líder del equipo puede entregar el proyecto.');
        }

        if ($team->project) {
            return redirect()->route('projects.show', $team->project);
        }

        return view('projects.create', compact('team'));
    }

    public function store(StoreProjectRequest $request)
    {
        $validated = $request->validated();

        $team = Team::findOrFail($validated['team_id']);

        if (!$team->event->allowsProjectActions()) {
            return back()->with('error', 'No se pueden entregar proyectos porque el evento no está en curso.');
        }

        if ($team->leader_id !== Auth::id()) {
            abort(403, 'Solo el líder del equipo puede entregar el proyecto.');
        }

        if ($team->project) {
            return redirect()->route('projects.show', $team->project)
                ->with('info', 'El proyecto ya fue entregado anteriormente.');
        }

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

        $project = Project::create([
            'name' => $request->name,
            'description' => $request->description,
            'repository_url' => $request->repository_url,
            'team_id' => $team->id,
            'documentation_path' => $documentationPath,
            'image_path' => $imagePath,
            'video_url' => $request->video_url,
        ]);

        ActivityLog::log('submitted', "Proyecto '{$project->name}' entregado por el equipo '{$team->name}'", $project, [
            'team_id' => $team->id,
            'team_name' => $team->name,
            'event_id' => $team->event_id,
        ]);

        return redirect()->route('events.show', $team->event_id)
            ->with('success', '¡Proyecto entregado exitosamente! 🚀');
    }

    public function show(Project $project)
    {
        if (Auth::user()->hasRole('judge')) {
            $isAssigned = $project->judges()->where('judge_id', Auth::id())->exists();
            if (!$isAssigned) {
                abort(403, 'Acceso denegado. No tienes asignada la evaluación de este proyecto.');
            }
        }

        $project->load(['team.members', 'team.leader', 'team.event', 'team.advisor', 'judges.judgeProfile', 'evaluations']);
        
        $availableJudges = [];
        if (Auth::user() && Auth::user()->can('projects.edit')) {
            $assignedJudgeIds = $project->judges->pluck('id')->toArray();
            
            $eventJudgeUserIds = $project->team->event->judges()->pluck('user_id');

            $availableJudges = User::role('judge')
                ->whereIn('id', $eventJudgeUserIds)
                ->whereNotIn('id', $assignedJudgeIds)
                ->with('judgeProfile.specialty')
                ->get();
        }
        
        return view('projects.show', compact('project', 'availableJudges'));
    }

    public function edit(Project $project)
    {
        $project->load(['team.leader', 'team.event', 'evaluations']);
        
        if (!$project->team->event->allowsProjectActions()) {
            return redirect()->route('projects.show', $project)
                ->with('error', 'No se puede editar el proyecto porque el evento no está en curso.');
        }
        
        $user = Auth::user();
        $isLeader = $project->team->leader_id === $user->id;
        $isAdminOrStaff = $user->hasAnyRole(['admin', 'staff']);
        
        if (!$isLeader && !$isAdminOrStaff) {
            abort(403, 'No tienes permiso para editar este proyecto.');
        }
        
        if ($project->evaluations()->exists()) {
            return redirect()->route('projects.show', $project)
                ->with('error', 'No se puede editar un proyecto que ya ha sido evaluado. Esto protege la integridad de las calificaciones.');
        }
        
        return view('projects.edit', compact('project'));
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        if (!$project->team->event->allowsProjectActions()) {
            return redirect()->route('projects.show', $project)
                ->with('error', 'No se puede editar el proyecto porque el evento no está en curso.');
        }
        
        $user = Auth::user();
        $isLeader = $project->team->leader_id === $user->id;
        $isAdminOrStaff = $user->hasAnyRole(['admin', 'staff']);
        
        if (!$isLeader && !$isAdminOrStaff) {
            abort(403, 'No tienes permiso para editar este proyecto.');
        }
        
        if ($project->evaluations()->exists()) {
            return redirect()->route('projects.show', $project)
                ->with('error', 'No se puede editar un proyecto que ya ha sido evaluado.');
        }
        
        $validated = $request->validated();

        if ($request->boolean('remove_documentation') && $project->documentation_path) {
            Storage::disk('public')->delete($project->documentation_path);
            $project->documentation_path = null;
        }

        if ($request->boolean('remove_image') && $project->image_path) {
            Storage::disk('public')->delete($project->image_path);
            $project->image_path = null;
        }

        if ($request->hasFile('documentation')) {
            if ($project->documentation_path) {
                Storage::disk('public')->delete($project->documentation_path);
            }
            $project->documentation_path = $request->file('documentation')->store(
                "projects/{$project->team->event_id}/{$project->team_id}/docs", 
                'public'
            );
        }

        if ($request->hasFile('image')) {
            if ($project->image_path) {
                Storage::disk('public')->delete($project->image_path);
            }
            $project->image_path = $request->file('image')->store(
                "projects/{$project->team->event_id}/{$project->team_id}/images", 
                'public'
            );
        }

        $project->name = $validated['name'];
        $project->description = $validated['description'];
        $project->repository_url = $validated['repository_url'];
        $project->video_url = $request->video_url;
        $project->save();
        
        return redirect()->route('projects.show', $project)
            ->with('success', 'Proyecto actualizado correctamente.');
    }

    public function destroy(Project $project)
    {
        $user = Auth::user();
        $isLeader = $project->team->leader_id === $user->id;
        $isAdminOrStaff = $user->hasAnyRole(['admin', 'staff']);
        
        if (!$isLeader && !$isAdminOrStaff) {
            abort(403, 'No tienes permiso para eliminar este proyecto.');
        }
        
        if ($project->evaluations()->exists()) {
            return back()->with('error', 'No se puede eliminar un proyecto que ya ha sido evaluado. Esto protege la integridad de los datos históricos.');
        }
        
        if ($project->team->awards()->exists()) {
            return back()->with('error', 'No se puede eliminar un proyecto cuyo equipo ha recibido premios.');
        }
        
        $eventId = $project->team->event_id;
        $projectName = $project->name;
        
        $project->judges()->detach();

        $project->deleteFiles();
        
        $project->delete();
        
        return redirect()->route('events.show', $eventId)
            ->with('success', "Proyecto \"{$projectName}\" eliminado correctamente.");
    }

    public function assignJudge(AssignJudgeRequest $request, Project $project)
    {
        if (!Auth::user()->can('projects.edit')) {
            abort(403);
        }

        if (!$project->team->event->allowsEvaluations()) {
            return back()->with('error', 'No se pueden asignar jueces porque el evento no está en curso.');
        }

        $validated = $request->validated();

        $judge = User::findOrFail($validated['judge_id']);
        if (!$judge->hasRole('judge')) {
            return back()->with('error', 'El usuario seleccionado no es un juez.');
        }

        if ($project->judges()->where('judge_id', $judge->id)->exists()) {
            return back()->with('error', 'Este juez ya está asignado al proyecto.');
        }

        $project->judges()->attach($judge->id, [
            'assigned_at' => now(),
            'is_completed' => false
        ]);

        return back()->with('success', "Juez {$judge->name} asignado correctamente.");
    }

    public function removeJudge(Project $project, User $judge)
    {
        if (!Auth::user()->can('projects.edit')) {
            abort(403);
        }

        $hasEvaluations = $project->evaluations()->where('judge_id', $judge->id)->exists();
        if ($hasEvaluations) {
            return back()->with('error', 'No se puede remover un juez que ya ha evaluado el proyecto.');
        }

        $project->judges()->detach($judge->id);

        return back()->with('success', "Juez {$judge->name} removido del proyecto.");
    }
}
