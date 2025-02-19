<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\tareas;

class TareaCreada extends Notification
{
    use Queueable;

    protected $tarea;

    /**
     * Create a new notification instance.
     */
    public function __construct(tareas $tarea)
    {
        $this->tarea = $tarea;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
        ->subject('Nueva Tarea Asignada')
        ->greeting('Hola ' . $notifiable->name . ',')
        ->line('Se te ha asignado una nueva tarea: "' . $this->tarea->titulo . '".')
        ->line('Descripción: ' . $this->tarea->descripcion)
        ->action('Ver Tarea', url('/tareas/' . $this->tarea->id))
        ->line('Por favor revisa y completa la tarea antes de la fecha límite.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
