<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Provider;
use Faker\Generator as Faker;

$factory->define(Provider::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'provider' => $faker->word,
        'username' => $faker->userName,
        'password' => $faker->password,
        'cost' => $faker->randomFloat(0, 0, 9999999999.),
        'type' => 'longcode',
        'team_id' => factory(\App\Team::class),
    ];
});
