<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Game;
use App\Genre;
use Faker\Generator as Faker;

$factory->define(Game::class, function (Faker $faker) {
    
    $genre = Genre::select('id')->inRandomOrder()->limit(1)->first();

    //$genre_id = ($genre_id) ? $genre_id : 1;

    $name = $faker->name;
    $name = str_replace(".", "", $name);
    $name = str_replace(" ", "-", $name);
    $name = str_replace("'", "-", $name);

    return [
        'name' => $name,
        'genre_id' => $genre->id,
        'status' => 'confirmed',
    ];
});
