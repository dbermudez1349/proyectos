<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class actividad extends Model
{
    protected $table = 'actividades';

    protected $fillable = ['tarea_id', 'usuario_id', 'comentario', 'archivos', 'tipo'];

    protected $casts = [
        'archivos' => 'array',
    ];

    public function usuario() {
        return $this->belongsTo(User::class);
    }

    public function tarea() {
        return $this->belongsTo(tareas::class);
    }
}
