<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class PopulateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $games = $this->get_game_list();

        foreach ($games as $game) {
            $genre = $this->genre_lookup($game['genre']);
            
            DB::table('games')->insert([
                ['name' => $game['name'], 'genre_id' => $genre->id],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $games = $this->get_game_list();

        foreach ($games as $game) {
            DB::table('games')->where('name', '=', $game['name'])->delete();
        }
    }

    private function genre_lookup($genre)
    {
        return Cache::remember('InstallGenre:' . $genre, 60, function () use($genre) {
            return DB::table('genres')->where('name', '=', $genre)->orWhere('short_name', '=', $genre)->first();
        });
    }

    public function get_game_list()
    {
        $games[] = array('name' => 'World of Warcraft', 'genre' => 'MMORPG');
        $games[] = array('name' => 'Overwatch', 'genre' => 'FPS');
        $games[] = array('name' => 'Minecraft', 'genre' => 'Sandbox');

        return $games;
    }
}
