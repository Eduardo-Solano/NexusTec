<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Event;
use App\Models\Team;
use App\Models\Award;
use Barryvdh\DomPDF\Facade\Pdf;

class DiplomaNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Event $event;
    protected Team $team;
    protected ?Award $award;
    protected string $type;

    public function __construct(Event $event, Team $team, ?Award $award = null, string $type = 'participation')
    {
        $this->event = $event;
        $this->team = $team;
        $this->award = $award;
        $this->type = $type;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $pdf = Pdf::loadView('exports.diploma', [
            'event' => $this->event,
            'participant' => $notifiable,
            'team' => $this->team,
            'project' => $this->team->project ?? null,
            'type' => $this->type,
            'award' => $this->award,
        ]);
        $pdf->setPaper('A4', 'landscape');

        $pdfContent = $pdf->output();
        $filename = $this->type === 'winner' 
            ? 'diploma_ganador_' . str_replace(' ', '_', $notifiable->name) . '.pdf'
            : 'diploma_participacion_' . str_replace(' ', '_', $notifiable->name) . '.pdf';

        if ($this->type === 'winner') {
            $positionLabel = Award::POSITIONS[$this->award->position] ?? 'Ganador';
            $subject = '🏆 ¡Felicidades! Diploma de ' . $positionLabel . ' - ' . $this->event->name;
            $greeting = '¡Felicidades, ' . $notifiable->name . '! 🎉';
            $introLines = [
                'Es un honor informarte que tu equipo **' . $this->team->name . '** ha obtenido el reconocimiento de **' . $positionLabel . '** en el evento **' . $this->event->name . '**.',
                'Adjunto encontrarás tu diploma de reconocimiento por este logro extraordinario.',
            ];
        } else {
            $subject = '📜 Diploma de Participación - ' . $this->event->name;
            $greeting = '¡Hola, ' . $notifiable->name . '!';
            $introLines = [
                'Gracias por tu participación en el evento **' . $this->event->name . '** como parte del equipo **' . $this->team->name . '**.',
                'Adjunto encontrarás tu diploma de participación como reconocimiento a tu esfuerzo y dedicación.',
            ];
        }

        $mail = (new MailMessage)
            ->subject($subject)
            ->greeting($greeting);

        foreach ($introLines as $line) {
            $mail->line($line);
        }

        if ($this->team->project) {
            $mail->line('**Proyecto:** ' . $this->team->project->name);
        }

        $mail->line('---')
            ->line('¡Esperamos verte en futuros eventos!')
            ->salutation('Atentamente, Comité Organizador - NexusTec')
            ->attachData($pdfContent, $filename, [
                'mime' => 'application/pdf',
            ]);

        return $mail;
    }

    public function toArray(object $notifiable): array
    {
        return [
            'event_id' => $this->event->id,
            'event_name' => $this->event->name,
            'team_id' => $this->team->id,
            'team_name' => $this->team->name,
            'type' => $this->type,
            'award_id' => $this->award?->id,
        ];
    }
}
