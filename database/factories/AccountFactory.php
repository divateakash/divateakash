<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Account;
use Faker\Generator as Faker;

$factory->define(Account::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'send_rate' => $faker->randomNumber(),
        'provider_id' => factory(\App\Provider::class),
        'team_id' => factory(\App\Team::class),
    ];
});
