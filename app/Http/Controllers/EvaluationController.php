<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\Project;
use App\Models\Criterion;
use App\Models\ActivityLog;
use App\Http\Requests\Evaluation\StoreEvaluationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <--- VITAL
use Illuminate\Support\Facades\DB;   // <--- VITAL

class EvaluationController extends Controller
{
    /**
     * Muestra la Rúbrica de Evaluación.
     */
    public function create(Request $request)
    {
        // 1. Validar que nos pasen el proyecto a calificar
        if (!$request->has('project_id')) {
            return back()->with('error', 'Proyecto no especificado.');
        }

        $project = Project::with(['team.event.criteria'])->findOrFail($request->project_id);

        // ⛔ Validar que el evento permita evaluaciones (estado activo)
        if (!$project->team->event->allowsEvaluations()) {
            return redirect()->route('projects.show', $project)
                ->with('error', 'No se pueden realizar evaluaciones porque el evento no está en curso.');
        }

        // 2. Seguridad: Verificar que el juez esté asignado a este proyecto
        $isAssigned = $project->judges()->where('judge_id', Auth::id())->exists();
        if (!$isAssigned) {
            return redirect()->route('projects.show', $project)
                ->with('error', 'No estás asignado para evaluar este proyecto.');
        }

        // 3. Seguridad: ¿Ya lo calificó este juez?
        $alreadyGraded = Evaluation::where('project_id', $project->id)
                                   ->where('judge_id', Auth::id())
                                   ->exists();

        if ($alreadyGraded) {
            return redirect()->route('projects.show', $project)
                ->with('error', 'Ya has evaluado este proyecto.');
        }

        // 4. Cargar los Criterios de Evaluación (La Rúbrica)
        $criteria = $project->team->event->criteria;
        if ($criteria->isEmpty()) {
            return back()->with('error', 'Este evento no tiene criterios de evaluación definidos.');
        }

        return view('evaluations.create', compact('project', 'criteria'));
    }

    /**
     * Guardar los puntajes.
     */
    public function store(StoreEvaluationRequest $request)
    {
        $validated = $request->validated();

        $project = Project::findOrFail($validated['project_id']);

        // ⛔ Validar que el evento permita evaluaciones (estado activo)
        if (!$project->team->event->allowsEvaluations()) {
            return redirect()->route('projects.show', $project)
                ->with('error', 'No se pueden guardar evaluaciones porque el evento no está en curso.');
        }

        // Seguridad: Verificar que el juez esté asignado a este proyecto
        $isAssigned = $project->judges()->where('judge_id', Auth::id())->exists();
        if (!$isAssigned) {
            return back()->with('error', 'No estás autorizado para evaluar este proyecto.');
        }

        // Verificar que no haya evaluado antes
        $alreadyGraded = Evaluation::where('project_id', $project->id)
                                   ->where('judge_id', Auth::id())
                                   ->exists();
        if ($alreadyGraded) {
            return redirect()->route('projects.show', $project)
                ->with('error', 'Ya has evaluado este proyecto.');
        }

        DB::transaction(function () use ($request, $project) {
            
            // Guardamos una fila por cada criterio evaluado
            foreach ($request->scores as $criterionId => $score) {
                Evaluation::create([
                    'project_id' => $request->project_id,
                    'judge_id' => Auth::id(),
                    'criterion_id' => $criterionId,
                    'score' => $score,
                    'feedback' => $request->feedback
                ]);
            }

            // Marcar como completado en la tabla pivot judge_project
            $project->judges()->updateExistingPivot(Auth::id(), [
                'is_completed' => true
            ]);

            // Registrar actividad
            $totalScore = array_sum($request->scores);
            ActivityLog::log('evaluated', "Proyecto '{$project->name}' evaluado por " . Auth::user()->name, $project, [
                'judge_id' => Auth::id(),
                'judge_name' => Auth::user()->name,
                'total_score' => $totalScore,
                'criteria_count' => count($request->scores),
            ]);
        });

        return redirect()->route('projects.show', $request->project_id)
            ->with('success', '¡Evaluación registrada correctamente! 🎉');
    }
    /**
     * Display the specified resource.
     */
    public function show(Evaluation $evaluation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Evaluation $evaluation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Evaluation $evaluation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Evaluation $evaluation)
    {
        //
    }
}
