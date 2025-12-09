<?php

namespace App\Notifications;

use App\Models\Team;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TeamJoinResponseNotification extends Notification
{
    use Queueable;

    protected Team $team;
    protected string $status;

    public function __construct(Team $team, string $status)
    {
        $this->team = $team;
        $this->status = $status;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        $accepted = $this->status === 'accepted';

        return [
            'type' => 'team_join_response',
            'status' => $this->status,
            'team_id' => $this->team->id,
            'team_name' => $this->team->name,
            'message' => $accepted
                ? 'Tu solicitud para unirte al equipo "' . $this->team->name . '" ha sido aceptada.'
                : 'Tu solicitud para unirte al equipo "' . $this->team->name . '" ha sido rechazada.',
            'team_url' => route('teams.show', $this->team),
        ];
    }
}
