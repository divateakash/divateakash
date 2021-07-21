<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\DomainGroup;
use Faker\Generator as Faker;

$factory->define(DomainGroup::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'domain_provider_id' => factory(\App\DomainProvider::class),
        'team_id' => factory(\App\Team::class),
    ];
});
