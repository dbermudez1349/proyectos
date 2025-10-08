<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('area')->insert([
            ['descripcion' => 'Planificacion', 'estado' => 1],
            ['descripcion' => 'Tecnologia', 'estado' => 1],
            ['descripcion' => 'Comunicacion', 'estado' => 1],
            ['descripcion' => 'Alcaldia', 'estado' => 1],
            ['descripcion' => 'Higiene', 'estado' => 1],
            ['descripcion' => 'Accion Social', 'estado' => 1],
        ]);
    }
}
