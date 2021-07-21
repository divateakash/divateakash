<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\MessageGroup;
use Faker\Generator as Faker;

$factory->define(MessageGroup::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'team_id' => factory(\App\Team::class),
    ];
});
