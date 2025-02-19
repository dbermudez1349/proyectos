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

          // Permisos
          Permission::firstOrCreate(['name' => 'ver proyectos']);
          Permission::firstOrCreate(['name' => 'crear proyectos']);
          Permission::firstOrCreate(['name' => 'crear tareas']);
          Permission::firstOrCreate(['name' => 'ver tareas propias']);
          Permission::firstOrCreate(['name' => 'realizar tareas']);

          // Asignar permisos a roles
          $admin->givePermissionTo(['ver proyectos', 'crear proyectos', 'crear tareas']);
          $director->givePermissionTo(['ver tareas propias', 'realizar tareas']);
    }
}
