<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Credit;
use Faker\Generator as Faker;

$factory->define(Credit::class, function (Faker $faker) {
    return [
        'amount' => $faker->randomNumber(),
        'team_id' => factory(\App\Team::class),
    ];
});
