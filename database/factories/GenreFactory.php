<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Genre;
use Faker\Generator as Faker;

$factory->define(Genre::class, function (Faker $faker) {
    
    $name = $faker->name;
    $name = str_replace(".", "", $name);
    $name = str_replace(" ", "-", $name);
    $name = str_replace("'", "-", $name);

    $temp = explode('-', $name);
    $short_name = '';
    foreach ($temp as $t) {
        $short_name .= $t[0];
    }


    return [
        'name' => $name,
        'short_name' => $short_name,
    ];
});
