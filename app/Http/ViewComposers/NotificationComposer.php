<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class NotificationComposer
{
  public function compose(View $view)
  {
    $pendingMembers = collect();

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
    }

    $view->with('pendingMembers', $pendingMembers);
  }
}
