<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Criterion;
use App\Models\Project;
use App\Models\JudgeProfile;
use App\Http\Requests\Event\StoreEventRequest;
use App\Http\Requests\Event\UpdateEventRequest;
use App\Http\Requests\Event\AssignJudgeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::withCount('teams');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $today = now();
            if ($request->status === 'active') {
                $query->where('end_date', '>=', $today);
            } elseif ($request->status === 'finished') {
                $query->where('end_date', '<', $today);
            }
        }

        if ($request->filled('date')) {
            $date = $request->date;
            $query->whereDate('start_date', '<=', $date)
                  ->whereDate('end_date', '>=', $date);
        } elseif ($request->filled('month') || $request->filled('year')) {
            if ($request->filled('month') && $request->filled('year')) {
                $year = $request->year;
                $month = str_pad($request->month, 2, '0', STR_PAD_LEFT);
                $startOfMonth = "{$year}-{$month}-01";
                $endOfMonth = date('Y-m-d', strtotime("last day of {$year}-{$month}"));
                
                $query->where(function ($q) use ($startOfMonth, $endOfMonth) {
                    $q->whereBetween('start_date', [$startOfMonth, $endOfMonth])
                      ->orWhereBetween('end_date', [$startOfMonth, $endOfMonth])
                      ->orWhere(function ($q3) use ($startOfMonth, $endOfMonth) {
                          $q3->where('start_date', '<=', $startOfMonth)
                             ->where('end_date', '>=', $endOfMonth);
                      });
                });
            } elseif ($request->filled('month')) {
                $month = str_pad($request->month, 2, '0', STR_PAD_LEFT);
                $query->where(function ($q) use ($month) {
                    $q->whereRaw('MONTH(start_date) = ?', [$month])
                      ->orWhereRaw('MONTH(end_date) = ?', [$month])
                      ->orWhereRaw('(MONTH(start_date) < ? AND MONTH(end_date) > ?)', [$month, $month]);
                });
            } elseif ($request->filled('year')) {
                $year = $request->year;
                $startOfYear = "{$year}-01-01";
                $endOfYear = "{$year}-12-31";
                
                $query->where(function ($q) use ($startOfYear, $endOfYear) {
                    $q->whereBetween('start_date', [$startOfYear, $endOfYear])
                      ->orWhereBetween('end_date', [$startOfYear, $endOfYear])
                      ->orWhere(function ($q3) use ($startOfYear, $endOfYear) {
                          $q3->where('start_date', '<=', $startOfYear)
                             ->where('end_date', '>=', $endOfYear);
                      });
                });
            }
        }

        $events = $query->orderBy('start_date', 'desc')->paginate(9)->withQueryString();

        return view('events.index', compact('events'));
    }

    public function create()
    {
        $criteria = Criterion::all(); 
        return view('events.create', compact('criteria'));
    }

    public function store(StoreEventRequest $request)
    {
        $validated = $request->validated();

        $totalPoints = Criterion::whereIn('id', $validated['criteria'])->sum('max_points');
        if ($totalPoints != 100) {
            return back()->withErrors(['criteria' => "La suma de los criterios seleccionados es $totalPoints. Debe ser exactamente 100 puntos."])->withInput();
        }

        $event = Event::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'start_date' => $validated['start_date'],
            'registration_deadline' => $validated['registration_deadline'],
            'end_date' => $validated['end_date'],
            'status' => Event::STATUS_REGISTRATION, 
        ]);

        $event->syncStatus();

        $event->criteria()->sync($validated['criteria']);
        
        return redirect()->route('events.index')
            ->with('success', 'Evento creado exitosamente.');
    }

    public function show(Event $event)
    {
        $event->syncStatus();
        
        $event->load(['teams.leader', 'teams.members', 'teams.project', 'criteria']);
        
        $projects = $event->teams->map(fn($team) => $team->project)->filter();
        
        $userHasTeam = false;
        $myTeam = null;
        $teamToAdvise = null;
        
        if (Auth::check()) {
            $myTeam = $event->teams()->whereHas('members', function($query) {
                $query->where('user_id', Auth::id())->where('is_accepted', true);
            })->with(['members', 'leader', 'project'])->first();
            
            $userHasTeam = $myTeam !== null;
            
            if (Auth::user()->hasRole('advisor')) {
                $teamToAdvise = $event->teams()
                    ->where('advisor_id', Auth::id())
                    ->with(['members', 'leader', 'project'])
                    ->first();
            }
        }
        
        return view('events.show', compact('event', 'userHasTeam', 'myTeam', 'teamToAdvise', 'projects'));
    }

    public function edit(Event $event)
    {
        $criteria = Criterion::all();
        $event->load('criteria'); 
        return view('events.edit', compact('event', 'criteria'));
    }

    public function update(UpdateEventRequest $request, Event $event)
    {
        $validated = $request->validated();

        $totalPoints = Criterion::whereIn('id', $validated['criteria'])->sum('max_points');
        if ($totalPoints != 100) {
            return back()->withErrors(['criteria' => "La suma de los criterios seleccionados es $totalPoints. Debe ser exactamente 100 puntos."])->withInput();
        }

        $status = $request->input('status', $event->status);
        if (!in_array($status, [Event::STATUS_REGISTRATION, Event::STATUS_ACTIVE, Event::STATUS_CLOSED])) {
            $status = $event->status;
        }

        $event->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'start_date' => $validated['start_date'],
            'registration_deadline' => $validated['registration_deadline'],
            'end_date' => $validated['end_date'],
            'status' => $status,
            'show_feedback_to_students' => $request->has('show_feedback_to_students'),
        ]);

        $event->criteria()->sync($validated['criteria']);
        
        return redirect()->route('events.index')
            ->with('success', 'Evento actualizado correctamente.');
    }

    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()->route('events.index')
            ->with('success', 'Evento eliminado del sistema.');
    }

    public function rankings(Event $event)
    {
        $user = Auth::user();
        $canAccess = $user->hasAnyRole(['admin', 'staff']) || $event->isClosed();
        
        if (!$canAccess) {
            return redirect()->route('events.show', $event)
                ->with('error', 'Los rankings estarán disponibles cuando finalice el evento.');
        }

        $event->load('criteria');
        $criteria = $event->criteria;

        $projects = Project::whereHas('team', function($query) use ($event) {
                $query->where('event_id', $event->id);
            })
            ->with([
                'team.leader',
                'team.members',
                'evaluations.criterion',
                'evaluations.judge',
                'judges'
            ])
            ->get();

        $projectsWithStats = $projects->map(function($project) use ($criteria) {
            $evaluations = $project->evaluations;
            
            $judgesCompleted = $project->judges()->wherePivot('is_completed', true)->count();
            $judgesTotal = $project->judges()->count();
            
            $scoresByCriterion = [];
            foreach ($criteria as $criterion) {
                $criterionEvaluations = $evaluations->where('criterion_id', $criterion->id);
                $scoresByCriterion[$criterion->id] = [
                    'name' => $criterion->name,
                    'max_points' => $criterion->max_points,
                    'average' => $criterionEvaluations->count() > 0 
                        ? round($criterionEvaluations->avg('score'), 2) 
                        : null,
                    'count' => $criterionEvaluations->count(),
                ];
            }
            
            $totalScore = collect($scoresByCriterion)->sum('average') ?? 0;
            
            $maxPossible = $criteria->sum('max_points');
            
            $percentage = $maxPossible > 0 ? round(($totalScore / $maxPossible) * 100, 1) : 0;

            return [
                'project' => $project,
                'team' => $project->team,
                'scores_by_criterion' => $scoresByCriterion,
                'total_score' => $totalScore,
                'max_possible' => $maxPossible,
                'percentage' => $percentage,
                'judges_completed' => $judgesCompleted,
                'judges_total' => $judgesTotal,
                'is_fully_evaluated' => $judgesTotal > 0 && $judgesCompleted === $judgesTotal,
            ];
        });

        $rankedProjects = $projectsWithStats->sortByDesc('total_score')->values();

        $stats = [
            'total_projects' => $projects->count(),
            'fully_evaluated' => $projectsWithStats->where('is_fully_evaluated', true)->count(),
            'pending_evaluation' => $projectsWithStats->where('is_fully_evaluated', false)->count(),
            'average_score' => $rankedProjects->avg('percentage'),
        ];

        return view('events.rankings', compact('event', 'rankedProjects', 'criteria', 'stats'));
    }

    public function assignJudge(AssignJudgeRequest $request, Event $event)
    {
        $validated = $request->validated();

        if ($event->judges()->where('judge_id', $validated['judge_id'])->exists()) {
            return back()->with('warning', 'Este juez ya está asignado a este evento.');
        }

        $event->judges()->attach($validated['judge_id']);

        return back()->with('success', 'Juez asignado exitosamente al evento.');
    }

    public function removeJudge(Event $event, JudgeProfile $judge)
    {
        if (!$event->judges()->where('judge_id', $judge->id)->exists()) {
            return back()->with('error', 'Este juez no está asignado a este evento.');
        }

        $event->judges()->detach($judge->id);

        return back()->with('success', 'Juez removido del evento exitosamente.');
    }
}
