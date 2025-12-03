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

    /**
     * Create a new notification instance.
     */
    public function __construct(Award $award)
    {
        $this->award = $award;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $medals = [
            '1er Lugar' => '🥇',
            '2do Lugar' => '🥈',
            '3er Lugar' => '🥉',
            'Mención Honorífica' => '🏅',
            'Mejor Innovación' => '💡',
            'Mejor Diseño' => '🎨',
            'Mejor Presentación' => '🎤',
            'Premio del Público' => '👥',
        ];

        $medal = $medals[$this->award->category] ?? '🏆';
        $teamName = $this->award->team->name ?? 'Tu equipo';
        $eventName = $this->award->event->name ?? 'el evento';
        $projectName = $this->award->team->project->title ?? 'tu proyecto';

        return (new MailMessage)
            ->subject("¡Felicidades! {$medal} Tu equipo ganó un premio - NexusTec")
            ->greeting("¡Felicidades, {$notifiable->name}!")
            ->line(new HtmlString('<div style="text-align: center; margin: 20px 0; padding: 30px; background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-radius: 16px; border: 2px solid #f59e0b;">
                <div style="font-size: 64px; margin-bottom: 15px;">' . $medal . '</div>
                <h2 style="color: #92400e; margin: 0 0 10px 0; font-size: 24px;">' . htmlspecialchars($this->award->category) . '</h2>
                <p style="color: #78350f; margin: 0; font-size: 14px;">Premio otorgado a</p>
                <p style="color: #92400e; font-weight: bold; font-size: 18px; margin: 5px 0;">' . htmlspecialchars($teamName) . '</p>
            </div>'))
            ->line("Tu equipo **\"{$teamName}\"** ha sido premiado en el evento **\"{$eventName}\"** por el proyecto **\"{$projectName}\"**.")
            ->line("Este logro es un reconocimiento al esfuerzo y dedicación de todo el equipo. ¡Enhorabuena!")
            ->action('Ver Ganadores', url('/winners/' . $this->award->event_id))
            ->line('¡Gracias por participar en NexusTec!')
            ->salutation(new HtmlString('Con orgullo,<br><strong>Equipo NexusTec</strong><br><em>Instituto Tecnológico de Oaxaca</em>'));
    }

    /**
     * Get the array representation of the notification for database.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'award_won',
            'award_id' => $this->award->id,
            'award_category' => $this->award->category,
            'award_name' => $this->award->name,
            'team_id' => $this->award->team_id,
            'team_name' => $this->award->team->name ?? null,
            'event_id' => $this->award->event_id,
            'event_name' => $this->award->event->name ?? null,
            'message' => "¡Tu equipo \"{$this->award->team->name}\" ganó {$this->award->category}!",
        ];
    }
}
