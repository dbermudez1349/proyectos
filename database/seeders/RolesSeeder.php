<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear roles
        $admin = Role::firstOrCreate(['name' => 'Administrador']);
        $director = Role::firstOrCreate(['name' => 'Director']);
        $coordinador = Role::firstOrCreate(['name' => 'Coordinador']);
        $alcalde = Role::firstOrCreate(['name' => 'Alcalde']);

        // Permisos
        Permission::firstOrCreate(['name' => 'ver administracion']);
        Permission::firstOrCreate(['name' => 'ver mistareas']);
        Permission::firstOrCreate(['name' => 'ver roles']);
        Permission::firstOrCreate(['name' => 'ver usuarios']);
        Permission::firstOrCreate(['name' => 'ver proyectos']);
        Permission::firstOrCreate(['name' => 'crear proyectos']);
        Permission::firstOrCreate(['name' => 'crear tareas']);
        Permission::firstOrCreate(['name' => 'ver tareas asignadas']);
        Permission::firstOrCreate(['name' => 'ver tareas detalladas']);
        Permission::firstOrCreate(['name' => 'completar tareas']);
        Permission::firstOrCreate(['name' => 'añadir actividades']);
        Permission::firstOrCreate(['name' => 'tablero de tareas']);
        Permission::firstOrCreate(['name' => 'tareas archivadas']);

        // Asignar permisos a roles
        // $admin->givePermissionTo(['ver administracion','ver mistareas', 'ver roles','ver usuarios','ver proyectos', 'crear proyectos', 'crear tareas','ver tareas asignadas','completar tareas','añadir actividades','tablero de tareas','tareas archivadas']);
        // $director->givePermissionTo(['ver tareas asignadas', 'completar tareas','añadir actividades']);
        // $alcalde->givePermissionTo(['ver tareas asignadas', 'ver tareas detalladas','tablero de tareas','añadir actividades']);
        // $coordinador->givePermissionTo(['ver usuarios','ver proyectos', 'crear proyectos', 'crear tareas','ver tareas asignadas','añadir actividades','tablero de tareas','tareas archivadas']);


        $admin->givePermissionTo(['ver administracion','ver mistareas', 'ver roles','ver usuarios','ver proyectos', 'crear tareas','tablero de tareas']);

        $director->givePermissionTo(['ver mistareas']);
        $alcalde->givePermissionTo(['ver mistareas']);
        $coordinador->givePermissionTo(['ver mistareas']);

    }
}
