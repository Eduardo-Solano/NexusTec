<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class EmailVerificationCode extends Notification
{
    use Queueable;

    public $code;

    public function __construct($code)
    {
        $this->code = $code;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Verifica tu correo - NexusTec')
            ->greeting('¡Hola!')
            ->line('Estás registrándote en NexusTec. Tu código de verificación es:')
            ->line(new HtmlString('<div style="text-align: center; margin: 20px 0;">
<span style="display: inline-block; font-size: 28px; font-weight: bold; letter-spacing: 4px; color: #ea580c; background-color: #fff7ed; padding: 15px 30px; border-radius: 8px; border: 2px solid #fed7aa; font-family: monospace;">' . $this->code . '</span>
</div>'))
            ->line('Este código expira en 30 minutos.')
            ->line('Si no solicitaste este registro, ignora este mensaje.')
            ->salutation('Saludos, Equipo NexusTec');
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}
