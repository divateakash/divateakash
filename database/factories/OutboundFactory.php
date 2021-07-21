<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Outbound;
use Faker\Generator as Faker;

$factory->define(Outbound::class, function (Faker $faker) {
    return [
        'from' => $faker->word,
        'to' => number($faker->e164PhoneNumber),
        'cost' => $faker->randomFloat(0, 0, 9999999999.),
        'hash' => $faker->word,
        'link' => $faker->word,
        'processed' => $faker->boolean,
        'success' => $faker->boolean,
        'error' => $faker->word,
        'send_at' => $faker->dateTime(),
        'sent_at' => $faker->dateTime(),
        'response' => $faker->word,
        'content' => $faker->text,
        'campaign_id' => factory(\App\Campaign::class),
        'offer_id' => factory(\App\Offer::class),
        'lead_id' => factory(\App\Lead::class),
        'account_id' => factory(\App\Account::class),
        'message_group_id' => factory(\App\MessageGroup::class),
        'message_id' => factory(\App\Message::class),
        'team_id' => factory(\App\Team::class),
    ];
});
