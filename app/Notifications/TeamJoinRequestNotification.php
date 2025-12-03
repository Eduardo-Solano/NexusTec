<?php

namespace App\Notifications;

use App\Models\Team;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TeamJoinRequestNotification extends Notification
{
    use Queueable;

    public Team $team;
    public User $user;

    public function __construct(Team $team, User $user)
    {
        $this->team = $team;
        $this->user = $user;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'join_request',
            'message' => "{$this->user->name} solicita unirse al equipo \"{$this->team->name}\"",
            'team_id' => $this->team->id,
            'user_id' => $this->user->id,
            'role' => $this->team->members()->where('user_id', $this->user->id)->first()->pivot->role,
            'accept_url' => route('teams.accept', [$this->team->id, $this->user->id, 'notification' => $this->id]),
            'reject_url' => route('teams.reject', [$this->team->id, $this->user->id, 'notification' => $this->id]),
            'notification_id' => $this->id,
        ];
    }

}
