<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Diego Bermudez',
            'email' => 'tecnologia.informacion@sanvicente.gob.ec',
            'password' => bcrypt('123456'),
        ]);
        DB::table('users')->insert([
            'name' => 'Ruben Solorzano',
            'email' => 'coordinacion@sanvicente.gob.ec',
            'password' => bcrypt('123456'),
        ]);

        DB::table('users')->insert([
            'name' => 'Ismael Rivero',
            'email' => 'planificacion@sanvicente.gob.ec',
            'password' => bcrypt('123456'),
        ]);

    }
}
