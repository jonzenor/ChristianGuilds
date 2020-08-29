<?php

namespace Tests;

use DB;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function createUser()
    {
        $user = factory(\App\User::class)->create();
        $user->encoded_name = str_replace("'", "&#039;", $user->name);

        return $user;
    }

    public function createAdminUser()
    {
        $user = factory(\App\User::class)->create();
        $admin = \App\Role::where('name', '=', 'Admin')->first();
        $user->roles()->attach($admin->id);

        return $user;
    }

    public function createGame()
    {
        $game = factory(\App\Game::class)->create();
        $game->encoded_name = str_replace("'", "&#039;", $game->name);

        return $game;
    }

    public function createGenre()
    {
        $genre = factory(\App\Genre::class)->create();
        $genre->encoded_name = str_replace("'", "&#039;", $genre->name);

        return $genre;
    }

    public function createGuild($user)
    {
        $guild = factory(\App\Guild::class)->create();
        $guild->owner_id = $user->id;
        $guild->save();
        
        DB::table('guild_members')->insert([
            'user_id' => $user->id,
            'guild_id' => $guild->id,
            'position' => 'owner',
            'title' => 'Test Game Manager',
        ]);
        
        $guild->encoded_name = str_replace("'", "&#039;", $guild->name);

        return $guild;
    }


}
