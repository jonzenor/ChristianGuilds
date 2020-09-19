<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Community;
use Faker\Generator as Faker;

$factory->define(Community::class, function (Faker $faker) {
    $name = $faker->name;
    $name = str_replace(".", "", $name);
    $name = str_replace(" ", "-", $name);
    $name = str_replace("'", "-", $name);

    return [
        'name' => $name,
        'description' => $faker->paragraph(),
        'owner_id' => 0,
    ];
});
