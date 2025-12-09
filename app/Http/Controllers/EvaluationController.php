<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\Project;
use App\Models\Criterion;
use App\Models\ActivityLog;
use App\Http\Requests\Evaluation\StoreEvaluationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EvaluationController extends Controller
{
    public function create(Request $request)
    {
        if (!$request->has('project_id')) {
            return back()->with('error', 'Proyecto no especificado.');
        }

        $project = Project::with(['team.event.criteria'])->findOrFail($request->project_id);

        if (!$project->team->event->allowsEvaluations()) {
            return redirect()->route('projects.show', $project)
                ->with('error', 'No se pueden realizar evaluaciones porque el evento no está en curso.');
        }

        $isAssigned = $project->judges()->where('judge_id', Auth::id())->exists();
        if (!$isAssigned) {
            return redirect()->route('projects.show', $project)
                ->with('error', 'No estás asignado para evaluar este proyecto.');
        }

        $alreadyGraded = Evaluation::where('project_id', $project->id)
                                   ->where('judge_id', Auth::id())
                                   ->exists();

        if ($alreadyGraded) {
            return redirect()->route('projects.show', $project)
                ->with('error', 'Ya has evaluado este proyecto.');
        }

        $criteria = $project->team->event->criteria;
        if ($criteria->isEmpty()) {
            return back()->with('error', 'Este evento no tiene criterios de evaluación definidos.');
        }

        return view('evaluations.create', compact('project', 'criteria'));
    }

    public function store(StoreEvaluationRequest $request)
    {
        $validated = $request->validated();

        $project = Project::findOrFail($validated['project_id']);

        if (!$project->team->event->allowsEvaluations()) {
            return redirect()->route('projects.show', $project)
                ->with('error', 'No se pueden guardar evaluaciones porque el evento no está en curso.');
        }

        $isAssigned = $project->judges()->where('judge_id', Auth::id())->exists();
        if (!$isAssigned) {
            return back()->with('error', 'No estás autorizado para evaluar este proyecto.');
        }

        $alreadyGraded = Evaluation::where('project_id', $project->id)
                                   ->where('judge_id', Auth::id())
                                   ->exists();
        if ($alreadyGraded) {
            return redirect()->route('projects.show', $project)
                ->with('error', 'Ya has evaluado este proyecto.');
        }

        DB::transaction(function () use ($request, $project) {
            
            foreach ($request->scores as $criterionId => $score) {
                Evaluation::create([
                    'project_id' => $request->project_id,
                    'judge_id' => Auth::id(),
                    'criterion_id' => $criterionId,
                    'score' => $score,
                    'feedback' => $request->feedback
                ]);
            }

            $project->judges()->updateExistingPivot(Auth::id(), [
                'is_completed' => true
            ]);

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

    public function show(Evaluation $evaluation)
    {
        //
    }

    public function edit(Evaluation $evaluation)
    {
        //
    }

    public function update(Request $request, Evaluation $evaluation)
    {
        //
    }

    public function destroy(Evaluation $evaluation)
    {
        //
    }
}
