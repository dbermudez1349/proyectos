<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TareaUsuario extends Model
{
    protected $connection = 'mysql';
    protected $table = 'tarea_usuario';
    protected $primaryKey  = 'id';
    public $timestamps = false;

}