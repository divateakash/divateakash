<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Carrier;
use Faker\Generator as Faker;

$factory->define(Carrier::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
    ];
});
