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

        if (Auth::check()) {
            $user = Auth::user();

            // Obtener equipos donde el usuario es líder
            $teams = $user->teams()->where('leader_id', $user->id)->get();

            // Obtener miembros pendientes de aceptación
            foreach ($teams as $team) {
                $pendingMembers = $pendingMembers->merge(
                    $team->members()->wherePivot('is_accepted', false)->get()
                );
            }

            // Obtener solicitudes de asesoría pendientes (para docentes/advisors)
            if ($user->hasAnyRole(['admin', 'staff', 'advisor'])) {
                $pendingAdvisories = Team::where('advisor_id', $user->id)
                    ->where('advisor_status', 'pending')
                    ->with('event')
                    ->get();
            }

            // Obtener proyectos asignados pendientes de evaluar (para jueces)
            if ($user->hasRole('judge')) {
                $pendingEvaluations = $user->assignedProjects()
                    ->wherePivot('is_completed', false)
                    ->with(['team.event'])
                    ->get();
            }
        }

        $view->with('pendingMembers', $pendingMembers);
        $view->with('pendingAdvisories', $pendingAdvisories);
        $view->with('pendingEvaluations', $pendingEvaluations);
    }
}