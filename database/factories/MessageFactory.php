<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Message;
use Faker\Generator as Faker;

$factory->define(Message::class, function (Faker $faker) {
    return [
        'content' => $faker->paragraphs(3, true),
        'message_group_id' => factory(\App\MessageGroup::class),
    ];
});
