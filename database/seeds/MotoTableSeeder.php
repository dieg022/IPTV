<?php

use Illuminate\Database\Seeder;

class MotoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //invocamos al factory que genera los users y le decimos que lo haga con 10
        factory(App\Moto::class,10)->create();
    }
}
