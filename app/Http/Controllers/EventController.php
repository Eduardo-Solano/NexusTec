<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Criterion;
use App\Models\Project;
use App\Models\JudgeProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Obtener todos los eventos con sus equipos asociados ordenados por fecha de inicio descendente.
        // Usamos 'paginate(9)' para que si hay 100 eventos, no explote la pantalla
        $query = Event::withCount('teams');

        // Filtro por búsqueda (nombre o descripción)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filtro por estado (activo/finalizado) - EXCLUSIVO
        if ($request->filled('status')) {
            $today = now();
            if ($request->status === 'active') {
                // Solo eventos que terminen hoy o después (activos)
                $query->where('end_date', '>=', $today);
            } elseif ($request->status === 'finished') {
                // Solo eventos que terminaron antes de hoy (finalizados)
                $query->where('end_date', '<', $today);
            }
        }

        // Filtro por fecha (día específico, mes, o año) - EXCLUSIVO
        if ($request->filled('date')) {
            // Eventos que estén activos en esta fecha específica
            $date = $request->date;
            $query->whereDate('start_date', '<=', $date)
                  ->whereDate('end_date', '>=', $date);
        } elseif ($request->filled('month') || $request->filled('year')) {
            // Filtro por mes y/o año
            if ($request->filled('month') && $request->filled('year')) {
                // Mes y año específicos
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
                // Solo mes (cualquier año)
                $month = str_pad($request->month, 2, '0', STR_PAD_LEFT);
                $query->where(function ($q) use ($month) {
                    $q->whereRaw('MONTH(start_date) = ?', [$month])
                      ->orWhereRaw('MONTH(end_date) = ?', [$month])
                      ->orWhereRaw('(MONTH(start_date) < ? AND MONTH(end_date) > ?)', [$month, $month]);
                });
            } elseif ($request->filled('year')) {
                // Solo año
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $criteria = Criterion::all(); // Traemos todos los criterios del catálogo
        return view('events.create', compact('criteria'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        // 1. Validaciones Robustas
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'start_date' => 'required|date',
            // Regla 'after': La fecha fin debe ser POSTERIOR a la fecha inicio
            'end_date' => 'required|date|after:start_date', 
            'criteria' => 'required|array|min:1', // Debe elegir al menos uno
            'criteria.*' => 'exists:criteria,id', // Que el ID exista
        ]);

        // VALIDACIÓN CUSTOM: Verificar que la suma de puntajes sea EXACTAMENTE 100
        $totalPoints = Criterion::whereIn('id', $validated['criteria'])->sum('max_points');
        if ($totalPoints != 100) {
            return back()->withErrors(['criteria' => "La suma de los criterios seleccionados es $totalPoints. Debe ser exactamente 100 puntos."])->withInput();
        }

        // 2. Crear el Evento
        $event = Event::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'status' => Event::STATUS_REGISTRATION, // Por defecto nace en inscripciones
        ]);

        // MAGIA ELOQUENT: Guardar relación Muchos a Muchos
        $event->criteria()->sync($validated['criteria']);
        // 3. Redireccionar con Mensaje de Éxito
        return redirect()->route('events.index')
            ->with('success', 'Evento creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
       // Cargamos los equipos y criterios
        $event->load(['teams.leader', 'teams.members', 'teams.project', 'criteria']);
        
        // Cargar proyectos del evento (a través de los equipos)
        // Esto crea una colección de proyectos filtrando los equipos que sí tienen proyecto
        $projects = $event->teams->map(fn($team) => $team->project)->filter();
        
        $userHasTeam = false;
        $myTeam = null;
        $teamToAdvise = null;
        
        if (Auth::check()) {
            // Verificar si el usuario es miembro de un equipo en este evento
            $myTeam = $event->teams()->whereHas('members', function($query) {
                $query->where('user_id', Auth::id())->where('is_accepted', true);
            })->with(['members', 'leader', 'project'])->first();
            
            $userHasTeam = $myTeam !== null;
            
            // Verificar si el usuario es asesor de algún equipo en este evento
            if (Auth::user()->hasRole('advisor')) {
                $teamToAdvise = $event->teams()
                    ->where('advisor_id', Auth::id())
                    ->with(['members', 'leader', 'project'])
                    ->first();
            }
        }
        
        return view('events.show', compact('event', 'userHasTeam', 'myTeam', 'teamToAdvise', 'projects'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        $criteria = Criterion::all();
        // Cargamos los criterios que este evento YA tiene asignados
        $event->load('criteria'); 
        return view('events.edit', compact('event', 'criteria'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        //
        // 1. Validar (Igual que en store, pero a veces es bueno permitir no cambiar nada)
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            // El checkbox si no se marca no se envía, así que lo manejamos abajo
            'criteria' => 'required|array|min:1', // Debe elegir al menos uno
        ]);

        // VALIDACIÓN CUSTOM: Verificar que la suma de puntajes sea EXACTAMENTE 100
        $totalPoints = Criterion::whereIn('id', $validated['criteria'])->sum('max_points');
        if ($totalPoints != 100) {
            return back()->withErrors(['criteria' => "La suma de los criterios seleccionados es $totalPoints. Debe ser exactamente 100 puntos."])->withInput();
        }

        // Validar el status si se envía
        $status = $request->input('status', $event->status);
        if (!in_array($status, [Event::STATUS_REGISTRATION, Event::STATUS_ACTIVE, Event::STATUS_CLOSED])) {
            $status = $event->status;
        }

        // 2. Actualizar
        $event->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'status' => $status,
            'show_feedback_to_students' => $request->has('show_feedback_to_students'),
        ]);

        // Actualizar criterios (Muchos a Muchos)
        $event->criteria()->sync($validated['criteria']);
        // 3. Redireccionar
        return redirect()->route('events.index')
            ->with('success', 'Evento actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        // Simplemente lo borramos
        $event->delete();

        return redirect()->route('events.index')
            ->with('success', 'Evento eliminado del sistema.');
    }

    /**
     * Mostrar Rankings/Resultados del evento
     */
    public function rankings(Event $event)
    {
        // Validación de acceso: Staff/Admin siempre, otros solo si evento cerrado
        $user = Auth::user();
        $canAccess = $user->hasAnyRole(['admin', 'staff']) || $event->isClosed();
        
        if (!$canAccess) {
            return redirect()->route('events.show', $event)
                ->with('error', 'Los rankings estarán disponibles cuando finalice el evento.');
        }

        // Cargar criterios del evento
        $event->load('criteria');
        $criteria = $event->criteria;

        // Obtener todos los proyectos del evento con sus evaluaciones
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

        // Calcular estadísticas para cada proyecto
        $projectsWithStats = $projects->map(function($project) use ($criteria) {
            // Obtener todas las evaluaciones del proyecto
            $evaluations = $project->evaluations;
            
            // Jueces que han evaluado
            $judgesCompleted = $project->judges()->wherePivot('is_completed', true)->count();
            $judgesTotal = $project->judges()->count();
            
            // Calcular puntaje por criterio (promedio de todos los jueces)
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
            
            // Puntaje total (suma de promedios por criterio)
            $totalScore = collect($scoresByCriterion)->sum('average') ?? 0;
            
            // Puntaje máximo posible
            $maxPossible = $criteria->sum('max_points');
            
            // Porcentaje
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

        // Ordenar por puntaje total (descendente)
        $rankedProjects = $projectsWithStats->sortByDesc('total_score')->values();

        // Estadísticas generales del evento
        $stats = [
            'total_projects' => $projects->count(),
            'fully_evaluated' => $projectsWithStats->where('is_fully_evaluated', true)->count(),
            'pending_evaluation' => $projectsWithStats->where('is_fully_evaluated', false)->count(),
            'average_score' => $rankedProjects->avg('percentage'),
        ];

        return view('events.rankings', compact('event', 'rankedProjects', 'criteria', 'stats'));
    }

    /**
     * Asignar un juez a un evento
     */
    public function assignJudge(Request $request, Event $event)
    {
        $validated = $request->validate([
            'judge_id' => 'required|exists:judge_profiles,id',
        ]);

        // Evitar duplicados
        if ($event->judges()->where('judge_id', $validated['judge_id'])->exists()) {
            return back()->with('warning', 'Este juez ya está asignado a este evento.');
        }

        $event->judges()->attach($validated['judge_id']);

        return back()->with('success', 'Juez asignado exitosamente al evento.');
    }

    /**
     * Remover un juez de un evento
     */
    public function removeJudge(Event $event, JudgeProfile $judge)
    {
        if (!$event->judges()->where('judge_id', $judge->id)->exists()) {
            return back()->with('error', 'Este juez no está asignado a este evento.');
        }

        $event->judges()->detach($judge->id);

        return back()->with('success', 'Juez removido del evento exitosamente.');
    }
}
