<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Reply;
use Faker\Generator as Faker;

$factory->define(Reply::class, function (Faker $faker) {
    return [
        'content' => $faker->paragraphs(3, true),
        'from' => $faker->word,
        'to' => $faker->word,
        'outbound_id' => factory(\App\Outbound::class),
        'reply_outbound_id' => factory(\App\Outbound::class),
        'campaign_id' => factory(\App\Campaign::class),
        'team_id' => factory(\App\Team::class),
    ];
});
