<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StaffAccountCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected string $plainPassword;
    protected string $roleLabel;

    public function __construct(string $plainPassword, string $roleLabel)
    {
        $this->plainPassword = $plainPassword;
        $this->roleLabel = $roleLabel;
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
            ->line("Se ha creado una cuenta para ti en {$appName} con rol: {$this->roleLabel}.")
            ->line("Correo: {$notifiable->email}")
            ->line("Contraseña temporal: {$this->plainPassword}")
            ->action('Ingresar a la plataforma', $loginUrl)
            ->line('Por seguridad, inicia sesión y cambia tu contraseña cuanto antes.');
    }
}
