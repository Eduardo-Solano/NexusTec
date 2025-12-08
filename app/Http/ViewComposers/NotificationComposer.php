<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\Team;
use App\Models\Project;

class NotificationComposer
{
    public function compose(View $view)
    {
        $pendingMembers = collect();
        $pendingAdvisories = collect();
        $pendingEvaluations = collect();
        $unreadNotifications = collect();

        if (Auth::check()) {
            $user = Auth::user();
            $userId = $user->id;
            
            // Cache de notificaciones por 30 segundos para evitar consultas repetidas
            $cacheKey = "user_notifications_{$userId}";
            
            $notificationData = Cache::remember($cacheKey, 30, function () use ($user) {
                $data = [
                    'pendingMembers' => collect(),
                    'pendingAdvisories' => collect(),
                    'pendingEvaluations' => collect(),
                    'unreadNotifications' => collect(),
                ];

                // 1. Equipos donde el usuario es líder - Una sola consulta optimizada
                $teamIds = $user->teams()
                    ->where('leader_id', $user->id)
                    ->pluck('teams.id');

                // 2. Solicitudes de unión pendientes - Una consulta
                if ($teamIds->isNotEmpty()) {
                    $data['pendingMembers'] = \App\Models\User::whereHas('teams', function ($q) use ($teamIds) {
                        $q->whereIn('teams.id', $teamIds)
                          ->where('team_user.is_accepted', false)
                          ->where('team_user.requested_by_user', true);
                    })->select('id', 'name', 'email')->get();
                }

                // 3. Solicitudes de asesoría pendientes
                if ($user->hasAnyRole(['admin', 'staff', 'advisor'])) {
                    $data['pendingAdvisories'] = Team::where('advisor_id', $user->id)
                        ->where('advisor_status', 'pending')
                        ->select('id', 'name', 'event_id')
                        ->with('event:id,name')
                        ->get();
                }

                // 4. Proyectos pendientes de evaluar
                if ($user->hasRole('judge')) {
                    $data['pendingEvaluations'] = $user->assignedProjects()
                        ->wherePivot('is_completed', false)
                        ->select('projects.id', 'projects.name', 'projects.team_id')
                        ->with(['team:id,name,event_id', 'team.event:id,name'])
                        ->get();
                }

                // 5. Notificaciones de BD (solo las últimas 10)
                $data['unreadNotifications'] = $user->unreadNotifications()
                    ->latest()
                    ->take(10)
                    ->get();

                return $data;
            });

            $pendingMembers = $notificationData['pendingMembers'];
            $pendingAdvisories = $notificationData['pendingAdvisories'];
            $pendingEvaluations = $notificationData['pendingEvaluations'];
            $unreadNotifications = $notificationData['unreadNotifications'];
        }

        // Pasar datos a la vista
        $view->with('pendingMembers', $pendingMembers);
        $view->with('pendingAdvisories', $pendingAdvisories);
        $view->with('pendingEvaluations', $pendingEvaluations);
        $view->with('unreadNotifications', $unreadNotifications);
    }
}
