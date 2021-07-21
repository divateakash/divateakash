<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Number;
use Faker\Generator as Faker;

$factory->define(Number::class, function (Faker $faker) {
    return [
        'number' => number($faker->e164PhoneNumber),
        'status' => $faker->word,
        'provider_id' => factory(\App\Provider::class),
        'account_id' => factory(\App\Account::class),
    ];
});
