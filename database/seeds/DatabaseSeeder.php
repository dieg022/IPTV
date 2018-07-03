<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //invocamos al seeder de usuarios
        $this->call(UsersTableSeeder::class);

        //invocamos al seeder de motos
       // $this->call(MotoTableSeeder::class); //comentado pk lo invocamos desde el seed de user y rellenamos la relacion
    }
}
