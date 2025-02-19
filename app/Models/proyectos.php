<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class proyectos extends Model
{
    protected $table = 'proyectos';
    use HasFactory;
    protected $fillable = ['nombre', 'descripcion'];
    public function tareas() {
        return $this->hasMany(tareas::class);
    }
}
