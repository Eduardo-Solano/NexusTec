<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Team;
use App\Models\User;
use App\Models\Career;
use App\Models\Project;
use App\Models\Award;
use App\Models\Evaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ParticipantsExport;

class ReportController extends Controller
{
    public function index()
    {
        $stats = [
            'total_events' => Event::count(),
            'active_events' => Event::whereIn('status', [Event::STATUS_REGISTRATION, Event::STATUS_ACTIVE])->count(),
            'total_teams' => Team::count(),
            'total_projects' => Project::count(),
            'total_students' => User::role('student')->count(),
            'total_judges' => User::role('judge')->count(),
            'total_awards' => Award::count(),
            'total_evaluations' => Evaluation::distinct('project_id', 'judge_id')->count(),
        ];

        $participationByMonth = Team::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count")
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $topCareers = DB::table('student_profiles')
            ->join('careers', 'student_profiles.career_id', '=', 'careers.id')
            ->join('team_user', 'student_profiles.user_id', '=', 'team_user.user_id')
            ->select('careers.name', 'careers.code', DB::raw('COUNT(DISTINCT team_user.user_id) as participants'))
            ->groupBy('careers.id', 'careers.name', 'careers.code')
            ->orderByDesc('participants')
            ->take(5)
            ->get();

        $topEvents = Event::withCount('teams')
            ->orderByDesc('teams_count')
            ->take(5)
            ->get();

        $topProjects = Project::with(['team.event'])
            ->withAvg('evaluations', 'score')
            ->withCount('evaluations')
            ->get()
            ->filter(fn($p) => $p->evaluations_count > 0 && $p->evaluations_avg_score > 0)
            ->sortByDesc('evaluations_avg_score')
            ->take(5)
            ->values();

        return view('reports.index', compact(
            'stats',
            'participationByMonth',
            'topCareers',
            'topEvents',
            'topProjects'
        ));
    }

    public function byEvent(Request $request)
    {
        $events = Event::withCount(['teams', 'awards', 'criteria'])
            ->orderBy('created_at', 'desc')
            ->get();

        $selectedEvent = null;
        $eventStats = [
            'total_teams' => 0,
            'total_projects' => 0,
            'total_participants' => 0,
            'total_judges' => 0,
            'total_evaluations' => 0,
            'teams_by_status' => [],
            'avg_score' => 0,
            'max_score' => 0,
            'min_score' => 0,
            'evaluated_projects' => 0,
            'top_projects' => [],
        ];

        if ($request->filled('event_id')) {
            $selectedEvent = Event::with(['teams.members', 'teams.project.evaluations', 'teams.project.judges', 'awards.team'])
                ->withCount(['teams', 'awards'])
                ->findOrFail($request->event_id);

            $teamsWithProject = $selectedEvent->teams->filter(fn($t) => $t->project !== null);
            $totalMembers = $selectedEvent->teams->sum(fn($t) => $t->members->count());

            $projectIds = $teamsWithProject->pluck('project.id')->filter();
            $totalJudges = DB::table('judge_project')
                ->whereIn('project_id', $projectIds)
                ->distinct('judge_id')
                ->count('judge_id');

            $evaluationsQuery = Evaluation::whereIn('project_id', $projectIds);
            $totalEvaluations = $evaluationsQuery->count();
            
            $scoreStats = Evaluation::whereIn('project_id', $projectIds)
                ->selectRaw('AVG(score) as avg_score, MAX(score) as max_score, MIN(score) as min_score')
                ->first();

            $teamsByStatus = $selectedEvent->teams->groupBy('status')
                ->map(fn($teams) => $teams->count())
                ->toArray();

            $topProjects = Project::with(['team'])
                ->whereIn('id', $projectIds)
                ->withAvg('evaluations', 'score')
                ->withCount('evaluations')
                ->get()
                ->filter(fn($p) => $p->evaluations_count > 0)
                ->sortByDesc('evaluations_avg_score')
                ->take(10)
                ->values();

            $eventStats = [
                'total_teams' => $selectedEvent->teams_count,
                'total_projects' => $teamsWithProject->count(),
                'total_participants' => $totalMembers,
                'total_judges' => $totalJudges,
                'total_evaluations' => $totalEvaluations,
                'teams_by_status' => $teamsByStatus,
                'avg_score' => $scoreStats->avg_score ?? 0,
                'max_score' => $scoreStats->max_score ?? 0,
                'min_score' => $scoreStats->min_score ?? 0,
                'evaluated_projects' => $topProjects->count(),
                'top_projects' => $topProjects,
            ];
        }

        return view('reports.by-event', compact('events', 'selectedEvent', 'eventStats'));
    }

    public function byCareer(Request $request)
    {
        $events = Event::orderBy('start_date', 'desc')->get();
        
        $query = Career::query();
        
        if ($request->filled('event_id')) {
            $careers = Career::select('careers.*')
                ->selectSub(function($q) use ($request) {
                    $q->from('student_profiles')
                        ->join('team_user', 'student_profiles.user_id', '=', 'team_user.user_id')
                        ->join('teams', 'team_user.team_id', '=', 'teams.id')
                        ->whereColumn('student_profiles.career_id', 'careers.id')
                        ->where('teams.event_id', $request->event_id)
                        ->selectRaw('COUNT(DISTINCT student_profiles.user_id)');
                }, 'students_count')
                ->orderBy('name')
                ->get();
        } else {
            $careers = Career::withCount('studentProfiles as students_count')
                ->orderBy('name')
                ->get();
        }

        return view('reports.by-career', compact('careers', 'events'));
    }

    public function byPeriod(Request $request)
    {
        $startDate = $request->filled('start_date') 
            ? \Carbon\Carbon::parse($request->start_date) 
            : now()->subDays(30);
        $endDate = $request->filled('end_date') 
            ? \Carbon\Carbon::parse($request->end_date) 
            : now();

        $periodStats = [
            'new_users' => User::whereBetween('created_at', [$startDate, $endDate])->count(),
            'new_teams' => Team::whereBetween('created_at', [$startDate, $endDate])->count(),
            'new_projects' => Project::whereBetween('created_at', [$startDate, $endDate])->count(),
            'new_evaluations' => Evaluation::whereBetween('created_at', [$startDate, $endDate])->count(),
        ];

        $dailyActivity = Team::selectRaw("DATE(created_at) as date, COUNT(*) as count")
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->toArray();

        $eventsInPeriod = Event::withCount('teams')
            ->where(function($q) use ($startDate, $endDate) {
                $q->whereBetween('start_date', [$startDate, $endDate])
                  ->orWhereBetween('end_date', [$startDate, $endDate])
                  ->orWhere(function($q2) use ($startDate, $endDate) {
                      $q2->where('start_date', '<=', $startDate)
                         ->where('end_date', '>=', $endDate);
                  });
            })
            ->orderBy('start_date')
            ->get();

        return view('reports.by-period', compact(
            'startDate',
            'endDate',
            'periodStats',
            'dailyActivity',
            'eventsInPeriod'
        ));
    }

    public function exportParticipants(Request $request)
    {
        $eventId = $request->get('event_id');
        
        if (!$eventId) {
            return back()->with('error', 'Debe seleccionar un evento para exportar.');
        }

        $event = Event::findOrFail($eventId);
        
        return Excel::download(
            new ParticipantsExport($event),
            "participantes-{$event->id}.xlsx"
        );
    }
}
