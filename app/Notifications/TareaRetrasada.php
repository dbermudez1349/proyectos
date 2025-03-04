<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\tareas;

class TareaRetrasada extends Notification
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
                    ->subject('⚠️ Tarea Retrasada: ' . $this->tarea->titulo)
                    ->greeting('Hola ' . $notifiable->name . ',')
                    ->line('La tarea "' . $this->tarea->titulo . '" ha pasado su fecha límite y se ha marcado como retrasada.')
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
