<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user1 = User::create([
            'name' => 'Diego Bermudez',
            'email' => 'tecnologia.informacion@sanvicente.gob.ec',
            'password' => bcrypt('123456'),
            'id_area' => 1,
        ]);
        $user1->assignRole('Administrador');
        $user2 = User::create([
            'name' => 'Ruben Solorzano',
            'email' => 'coordinacion@sanvicente.gob.ec',
            'password' => bcrypt('123456'),
            'id_area' => 3,
        ]);
        $user2->assignRole('Coordinador');
        $user3 = User::create([
            'name' => 'Ismael Rivero',
            'email' => 'planificacion@sanvicente.gob.ec',
            'password' => bcrypt('123456'),
            'id_area' => 2,
        ]);
        $user3->assignRole('Director');
        $user4 = User::create([
            'name' => 'Fabricio Lara',
            'email' => 'alcaldia@sanvicente.gob.ec',
            'password' => bcrypt('123456'),
            'id_area' => 4,
        ]);
        $user4->assignRole('Alcalde');
        $user5 = User::create([
            'name' => 'Karen Quintero',
            'email' => 'higiene@sanvicente.gob.ec',
            'password' => bcrypt('123456'),
            'id_area' => 5,
        ]);
        $user5->assignRole('Director');
        $user6 = User::create([
            'name' => 'Marcos Cevallos',
            'email' => 'accion.social@sanvicente.gob.ec',
            'password' => bcrypt('123456'),
            'id_area' => 6,
        ]);
        $user6->assignRole('Director');

    }
}
