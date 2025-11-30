<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\Team;
use App\Models\User;
use App\Models\Project;

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
                'recent_teams' => Team::with('event')->latest()->take(5)->get(),
            ];

            // LÓGICA NUEVA: Solicitudes de Asesoría Pendientes
            $data['pending_advisories'] = Project::where('advisor_id', $user->id)
                                                 ->where('advisor_status', 'pending')
                                                 ->with('team')
                                                 ->get();
            
            // Proyectos ya aceptados
            $data['my_projects'] = Project::where('advisor_id', $user->id)
                                          ->where('advisor_status', 'accepted')
                                          ->count();
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
