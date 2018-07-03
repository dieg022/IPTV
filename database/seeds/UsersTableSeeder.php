<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //invocamos al factory que genera los users y le decimos que lo haga con 10
       // factory(App\User::class,10)->create();

        //factory para la relacion, creo 10 usuarios y por cada uno de ellos creo una moto
        //dadas las definiciones en la relacion del metodo motos() de la clase usuario, ha
        //de encontrar la tabla pivote y rellenarla auto
        factory(App\User::class, 10)->create()->each(function ($user) {
            //$user->motos()->save(factory(App\Moto::class)->make());
            $ubicacion = factory(App\Ubicacion::class)->create();
            $ubicacion->user_id=$user->id;
            //$user->ubicaciones()->save($ubicacion);
            $ubicacion->save();
        });

    }
}
