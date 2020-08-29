<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Guild;
use Faker\Generator as Faker;

$factory->define(Guild::class, function (Faker $faker) {

    $name = $faker->name;
    $name = str_replace(".", "", $name);
    $name = str_replace(" ", "-", $name);
    $name = str_replace("'", "-", $name);

    return [
        'name' => $name,
        'game_id' => 1,
        'owner_id' => 0,
    ];
});
