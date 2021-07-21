<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Domain;
use Faker\Generator as Faker;

$factory->define(Domain::class, function (Faker $faker) {
    return [
        'domain' => $faker->word,
        'status' => $faker->word,
        'points_to' => $faker->word,
        'dns_last_updated_at' => $faker->dateTime(),
        'expires_at' => $faker->dateTime(),
        'domain_group_id' => factory(\App\DomainGroupId::class),
        'domain_provider_id' => factory(\App\DomainProvider::class),
        'error' => $faker->word,
        'errored_at' => $faker->dateTime(),
        'team_id' => factory(\App\Team::class),
    ];
});
