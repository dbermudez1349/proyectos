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
        ]);
        $user1->assignRole('Administrador');
        $user2 = User::create([
            'name' => 'Ruben Solorzano',
            'email' => 'coordinacion@sanvicente.gob.ec',
            'password' => bcrypt('123456'),
        ]);
        $user2->assignRole('Administrador');
        $user3 = User::create([
            'name' => 'Ismael Rivero',
            'email' => 'planificacion@sanvicente.gob.ec',
            'password' => bcrypt('123456'),
        ]);
        $user3->assignRole('Director');

    }
}
