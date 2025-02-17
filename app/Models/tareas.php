<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class tareas extends Model
{
    use HasFactory;
    protected $table = 'tareas';
    /*protected $fillable = ['proyecto_id', 'titulo', 'descripcion', 'usuario_id', 'estado', 'fecha_limite', 'archivo', 'archivar'];

    public function proyecto() {
        return $this->belongsTo(proyectos::class);
    }
    public function usuario() {
        return $this->belongsTo(User::class);
    }*/

    protected $fillable = ['proyecto_id','titulo', 'descripcion', 'estado', 'fecha_limite', 'archivo', 'archivar'];

    public function usuarios() {
        return $this->belongsToMany(User::class, 'tarea_usuario','tarea_id','usuario_id');
    }

    public function actividades() {
        return $this->hasMany(actividad::class,'tarea_id','id');
    }

    public function proyecto(){
        return $this->belongsTo(proyectos::class);
    }
}
