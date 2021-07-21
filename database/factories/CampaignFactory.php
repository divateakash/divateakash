<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Campaign;
use Faker\Generator as Faker;

$factory->define(Campaign::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'status' => $faker->word,
        'link_type' => $faker->word,
        'message_type' => 'single',
        'carriers' => '{}',
        'team_id' => factory(\App\Team::class),
        'account_id' => factory(\App\Account::class),
        'message_group_id' => factory(\App\MessageGroup::class),
        'offer_id' => factory(\App\Offer::class),
        'catalog_id' => factory(\App\Catalog::class),
    ];
});
