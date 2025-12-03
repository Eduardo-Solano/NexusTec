<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
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

            // ======================================
            // 1. Equipos donde el usuario es líder
            // ======================================
            $teams = $user->teams()
                ->where('leader_id', $user->id)
                ->get();

            // ===================================================
            // 2. Solicitudes de unión (NO invitaciones del líder)
            //    Solo mostrar usuarios que pidieron unirse:
            //    requested_by_user = true
            //    is_accepted = false
            // ===================================================
            foreach ($teams as $team) {
                $pendingMembers = $pendingMembers->merge(
                    $team->members()
                        ->wherePivot('is_accepted', false)
                        ->wherePivot('requested_by_user', true)  // ← FILTRO CLAVE
                        ->get()
                );
            }

            // =======================================================
            // 3. Solicitudes de asesoría pendientes (staff / advisor)
            // =======================================================
            if ($user->hasAnyRole(['admin', 'staff', 'advisor'])) {
                $pendingAdvisories = Team::where('advisor_id', $user->id)
                    ->where('advisor_status', 'pending')
                    ->with('event')
                    ->get();
            }

            // =================================================
            // 4. Proyectos pendientes de evaluar (rol: judge)
            // =================================================
            if ($user->hasRole('judge')) {
                $pendingEvaluations = $user->assignedProjects()
                    ->wherePivot('is_completed', false)
                    ->with(['team.event'])
                    ->get();
            }

            // =================================================
            // 5. Notificaciones de BD (premios, invitaciones)
            // =================================================
            $unreadNotifications = $user->unreadNotifications()
                ->where('type', '!=', 'App\Notifications\AwardNotification') // <-- Oculta premios
                ->latest()
                ->take(10)
                ->get();

        }

        // Pasar datos a la vista
        $view->with('pendingMembers', $pendingMembers);
        $view->with('pendingAdvisories', $pendingAdvisories);
        $view->with('pendingEvaluations', $pendingEvaluations);
        $view->with('unreadNotifications', $unreadNotifications);
    }
}
