<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\tareas;

class tarea implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public tareas $tareas;
    )
    {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
    }
}
