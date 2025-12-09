<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
// 1. IMPORTANTE: Esta línea permite usar HTML en el correo
use Illuminate\Support\HtmlString;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

   public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Tu código de recuperación')
            ->greeting('Hola,')
            ->line('Has solicitado restablecer tu contraseña. Copia el siguiente código:')
            
            ->line(new HtmlString('<div style="text-align: center; margin: 20px 0;">
<span style="display: inline-block; font-size: 24px; font-weight: bold; letter-spacing: 3px; color: #2d3748; background-color: #edf2f7; padding: 15px 25px; border-radius: 8px; border: 1px solid #cbd5e0; font-family: monospace;">' . $this->token . '</span>
</div>'))
            
            ->line('Pega este código en la pantalla de verificación.')
            ->line('Si no fuiste tú, ignora este mensaje.');
    }
}