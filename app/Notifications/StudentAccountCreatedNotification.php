<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StudentAccountCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected string $plainPassword;

    public function __construct(string $plainPassword)
    {
        $this->plainPassword = $plainPassword;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $appName = config('app.name', 'NexusTec');
        $loginUrl = url('/login');

        return (new MailMessage)
            ->subject("Tu acceso a {$appName}")
            ->greeting("Hola {$notifiable->name}!")
            ->line("Se ha creado tu cuenta de estudiante en {$appName}.")
            ->line("Correo: {$notifiable->email}")
            ->line("Contraseña temporal: {$this->plainPassword}")
            ->action('Ingresar a la plataforma', $loginUrl)
            ->line('Inicia sesión y cambia tu contraseña al ingresar.');
    }
}
