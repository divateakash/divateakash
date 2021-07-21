<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\DomainProvider;
use Faker\Generator as Faker;

$factory->define(DomainProvider::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'user' => $faker->word,
        'password' => $faker->password,
        'provider' => $faker->word,
        'team_id' => factory(\App\Team::class),
    ];
});
