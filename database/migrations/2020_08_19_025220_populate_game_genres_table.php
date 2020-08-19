<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PopulateGameGenresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $genres = $this->get_genre_list();

        foreach ($genres as $genre) {
            DB::table('genres')->insert([
                ['name' => $genre['name'], 'short_name' => $genre['short_name']],
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
        $genres = $this->get_genre_list();

        foreach ($genres as $genre) {
            DB::table('genres')->where('name', '=', $genre['name'])->delete();
        }
    }

    public function get_genre_list()
    {
        $genres[] = array('short_name' => 'MMORPG', 'name' => 'Massively Multiplayer Online Role Playing Game');
        $genres[] = array('short_name' => 'FPS', 'name' => 'First Person Shooter');
        $genres[] = array('short_name' => 'Sandbox', 'name' => 'Sandbox');

        return $genres;
    }

}
