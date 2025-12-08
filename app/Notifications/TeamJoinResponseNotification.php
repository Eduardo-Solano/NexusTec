<?php

namespace App\Notifications;

use App\Models\Team;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TeamJoinResponseNotification extends Notification
{
    use Queueable;

    protected Team $team;
    protected string $status; // 'accepted' o 'rejected'

    /**
     * @param Team   $team
     * @param string $status  'accepted' | 'rejected'
     */
    public function __construct(Team $team, string $status)
    {
        $this->team = $team;
        $this->status = $status;
    }

    /**
     * Canales por los que se envía la notificación
     */
    public function via($notifiable): array
    {
        // Solo en base de datos (campanita)
        return ['database'];
    }

    /**
     * Datos que se guardan en la tabla notifications
     */
    public function toArray($notifiable): array
    {
        $accepted = $this->status === 'accepted';

        return [
            'type' => 'team_join_response', // 👈 clave para el navigation
            'status' => $this->status,        // 'accepted' o 'rejected'
            'team_id' => $this->team->id,
            'team_name' => $this->team->name,
            'message' => $accepted
                ? 'Tu solicitud para unirte al equipo "' . $this->team->name . '" ha sido aceptada.'
                : 'Tu solicitud para unirte al equipo "' . $this->team->name . '" ha sido rechazada.',
            'team_url' => route('teams.show', $this->team),
        ];
    }
}
