<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\Team;
use App\Models\User;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    //
    public function index()
    {
        $user = Auth::user();
        $data = [];

        if ($user->hasRole('admin') || $user->hasRole('staff') || $user->hasRole('advisor')) {
            // Lógica para ADMIN / STAFF / ADVISOR
            $data = [
                'total_students' => User::role('student')->count(),
                'active_events' => Event::where('is_active', true)->count(),
                'total_teams' => Team::count(),
                'projects_delivered' => Project::count(),
                'recent_teams' => Team::with(['event', 'leader', 'members'])->latest()->take(5)->get(),
            ];

            // Asesorías pendientes (solo para advisors)
            $data['pending_advisories'] = Team::where('advisor_id', $user->id)
                                            ->where('advisor_status', 'pending')
                                            ->with('event') // Cargamos el evento para mostrar contexto
                                            ->get();
            
            // 2. PROYECTOS ASESORADOS (Equipos aceptados)
            $data['my_projects'] = Team::where('advisor_id', $user->id)
                ->where('advisor_status', 'accepted')
                ->count();

            // Datos para gráficas - Equipos por día (últimos 14 días)
            $data['teams_by_day'] = Team::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->where('created_at', '>=', now()->subDays(14))
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            // Proyectos por evento
            $data['projects_by_event'] = Event::withCount('teams')
                ->orderBy('teams_count', 'desc')
                ->take(5)
                ->get();

            // Estudiantes por carrera (top 5)
            $data['students_by_career'] = DB::table('student_profiles')
                ->join('careers', 'student_profiles.career_id', '=', 'careers.id')
                ->select('careers.name', DB::raw('COUNT(*) as count'))
                ->groupBy('careers.id', 'careers.name')
                ->orderByDesc('count')
                ->take(5)
                ->get();
        } 
        elseif ($user->hasRole('student')) {
            // Lógica para ESTUDIANTE
            // Buscamos si tiene equipo en algún evento activo
            $myTeam = Team::whereHas('members', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->with(['event', 'project', 'members'])->latest()->first();

            // Buscamos eventos próximos para mostrarle
            $upcomingEvents = Event::where('is_active', true)
                                   ->where('start_date', '>', now())
                                   ->take(3)->get();

            $data = [
                'my_team' => $myTeam,
                'upcoming_events' => $upcomingEvents
            ];
        }

        return view('dashboard', compact('data'));
    }
}
