<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\FileUpload;
use Faker\Generator as Faker;

$factory->define(FileUpload::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'mapping' => '{}',
        'duplicates' => $faker->randomNumber(),
        'rejected' => $faker->randomNumber(),
        'catalog_id' => factory(\App\Catalog::class),
        'team_id' => factory(\App\Team::class),
    ];
});
