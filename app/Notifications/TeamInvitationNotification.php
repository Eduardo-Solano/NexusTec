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
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'team_invitation',
            'id' => $this->id,
            'message' => 'Has sido invitado al equipo "' . $this->team->name . '"',
            'team_id' => $this->team->id,
            'team_name' => $this->team->name,
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
