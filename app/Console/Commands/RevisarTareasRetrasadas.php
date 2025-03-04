<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\tareas;
use Carbon\Carbon;
use App\Notifications\TareaRetrasada;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class RevisarTareasRetrasadas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:revisar-tareas-retrasadas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Revisa las tareas con fecha límite vencida y las marca como retrasadas';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $hoy = Carbon::now();  // Fecha y hora actual

        // Buscar tareas con estado 'pendiente' que ya tengan la fecha límite pasada
        $tareas = tareas::where('estado', 'pendiente')
                        ->where('fecha_limite', '<', $hoy)  // Solo tareas cuyo límite ya pasó
                        ->get();

        // Revisamos si hay tareas retrasadas
        foreach ($tareas as $tarea) {
            // Si la tarea ya pasó la fecha límite, cambiamos su estado a 'retrasado'
            if ($tarea->fecha_limite && Carbon::parse($tarea->fecha_limite)->isPast()) {
                $tarea->estado = 'atrasada';
                $tarea->save();

                // Enviar notificación por correo a los usuarios asignados a la tarea
                foreach ($tarea->usuarios as $usuario) {
                    // Enviar correo notificando que la tarea está retrasada
                    $usuario->notify(new TareaRetrasada($tarea));
                }

                // Imprimir en consola que la tarea fue actualizada
                $this->info("Tarea {$tarea->id} marcada como retrasada.");
            }
        }

        // Si no hay tareas que procesar, avisar en consola
        if ($tareas->isEmpty()) {
            $this->info('No hay tareas retrasadas para revisar.');
        }
    }
}
