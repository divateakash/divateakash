<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Click;
use Faker\Generator as Faker;

$factory->define(Click::class, function (Faker $faker) {
    return [
        'bot' => $faker->boolean,
        'offer_id' => factory(\App\Offer::class),
        'campaign_id' => factory(\App\Campaign::class),
        'outbound_id' => factory(\App\Outbound::class),
        'team_id' => factory(\App\Team::class),
    ];
});
