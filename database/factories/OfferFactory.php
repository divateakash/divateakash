<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Offer;
use Faker\Generator as Faker;

$factory->define(Offer::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'redirect_url' => $faker->word,
        'team_id' => factory(\App\Team::class),
    ];
});
