<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Area extends Model
{
    protected $connection = 'mysql';
    protected $table = 'area';
    protected $primaryKey  = 'id';
    public $timestamps = false;

}