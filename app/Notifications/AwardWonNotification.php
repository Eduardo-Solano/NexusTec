<?php

namespace App\Notifications;

use App\Models\Award;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class AwardWonNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Award $award;

    public function __construct(Award $award)
    {
        $this->award = $award;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $positionLabel = Award::POSITIONS[$this->award->position] ?? 'Ganador';
        
        $medals = [
            1 => '🥇',
            2 => '🥈',
            3 => '🥉',
        ];

        $medal = $medals[$this->award->position] ?? '🏆';
        $teamName = $this->award->team->name ?? 'Tu equipo';
        $eventName = $this->award->event->name ?? 'el evento';
        $projectName = $this->award->team->project->name ?? 'tu proyecto';

        return (new MailMessage)
            ->subject("¡Felicidades! {$medal} Tu equipo ganó {$positionLabel} - NexusTec")
            ->greeting("¡Felicidades, {$notifiable->name}!")
            ->line(new HtmlString('<div style="text-align: center; margin: 20px 0; padding: 30px; background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-radius: 16px; border: 2px solid #f59e0b;">
                <div style="font-size: 64px; margin-bottom: 15px;">' . $medal . '</div>
                <h2 style="color: #92400e; margin: 0 0 10px 0; font-size: 24px;">' . htmlspecialchars($positionLabel) . '</h2>
                <p style="color: #78350f; margin: 0; font-size: 14px;">Premio otorgado a</p>
                <p style="color: #92400e; font-weight: bold; font-size: 18px; margin: 5px 0;">' . htmlspecialchars($teamName) . '</p>
            </div>'))
            ->line("Tu equipo **\"{$teamName}\"** ha sido premiado en el evento **\"{$eventName}\"** por el proyecto **\"{$projectName}\"**.")
            ->line("Este logro es un reconocimiento al esfuerzo y dedicación de todo el equipo. ¡Enhorabuena!")
            ->action('Ver Ganadores', url('/winners/' . $this->award->event_id))
            ->line('¡Gracias por participar en NexusTec!')
            ->salutation(new HtmlString('Con orgullo,<br><strong>Equipo NexusTec</strong><br><em>Instituto Tecnológico de Oaxaca</em>'));
    }

    public function toArray(object $notifiable): array
    {
        $positionLabel = Award::POSITIONS[$this->award->position] ?? 'Ganador';
        
        return [
            'type' => 'award_won',
            'award_id' => $this->award->id,
            'award_position' => $this->award->position,
            'team_id' => $this->award->team_id,
            'team_name' => $this->award->team->name ?? null,
            'event_id' => $this->award->event_id,
            'event_name' => $this->award->event->name ?? null,
            'message' => "¡Tu equipo \"{$this->award->team->name}\" ganó {$positionLabel}!",
        ];
    }
}
