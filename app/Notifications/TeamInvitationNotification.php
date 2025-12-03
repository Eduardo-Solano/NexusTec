<?php

namespace App\Notifications;

use App\Models\Team;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TeamInvitationNotification extends Notification
{
    use Queueable;

    public Team $team;

    public function __construct(Team $team)
    {
        $this->team = $team;
    }

    public function via($notifiable)
    {
        // Por ahora solo en base de datos (campanita)
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'id' => $this->id, // 🔥 IMPORTANTE: agregar esto
            'message' => 'Has sido invitado al equipo "' . $this->team->name . '"',
            'team_id' => $this->team->id,
            'accept_url' => route('teams.invitations.accept', [
                'team' => $this->team->id,
                'notification' => $this->id,
            ]),
            'reject_url' => route('teams.invitations.reject', [
                'team' => $this->team->id,
                'notification' => $this->id,
            ]),


        ];
    }


}
