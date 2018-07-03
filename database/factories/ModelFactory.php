<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */

//este es un factory para el modelo user
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password='secret';


    return [
        'name' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'nombre_completo' => $faker->name,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
        'tipocliente' => 'hotel',
    ];
});


$factory->define(App\Ubicacion::class, function (Faker\Generator $faker) {

    return [
        'nombre' => $faker->city,
        'direccion' => $faker->address,
        'user_id' => 1,
    ];
});

$factory->define(App\Moto::class, function (Faker\Generator $faker) {

    return [
        'modelo' => $faker->company,
        'kms' => $faker->randomDigitNotNull,
    ];
});

