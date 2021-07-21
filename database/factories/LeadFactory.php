<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Lead;
use Faker\Generator as Faker;

$factory->define(Lead::class, function (Faker $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email' => $faker->safeEmail,
        'phone' => $faker->phoneNumber,
        'timezone' => $faker->word,
        'region' => $faker->word,
        'carrier' => $faker->word,
        'type' => $faker->word,
        'start_hour' => 9,
        'end_hour' => 21,
        'carrier_id' => 1,
        'catalog_id' => factory(\App\Catalog::class),
        'team_id' => factory(\App\Team::class),
    ];
});
