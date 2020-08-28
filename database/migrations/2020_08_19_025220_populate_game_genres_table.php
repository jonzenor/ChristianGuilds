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
        $genres[] = array('short_name' => 'MMOFPS', 'name' => 'Massively Multiplayer Online First Person Shooter');
        $genres[] = array('short_name' => 'RTS', 'name' => 'Real-Time Strategy');
        $genres[] = array('short_name' => 'MMORTS', 'name' => 'Massively Multiplayer Online Real-time Strategy');
        $genres[] = array('short_name' => 'Survival', 'name' => 'Survival');
        $genres[] = array('short_name' => 'BR', 'name' => 'Battle Royale');
        $genres[] = array('short_name' => 'Simulation', 'name' => 'Simulation');
        $genres[] = array('short_name' => 'Sports', 'name' => 'Sports');
        $genres[] = array('short_name' => 'CCG', 'name' => 'Collectable Card Game');
        $genres[] = array('short_name' => 'RPG', 'name' => 'Role-playing Game');
        $genres[] = array('short_name' => 'JRPG', 'name' => 'Japanese Role-playing Game');
        $genres[] = array('short_name' => 'VR', 'name' => 'Virtual Reality');
        $genres[] = array('short_name' => 'RL', 'name' => 'Roguelike');
        $genres[] = array('short_name' => 'MOBA', 'name' => 'Multiplayer Online Battle Arena');
        $genres[] = array('short_name' => 'TBS', 'name' => 'Turn-based Strategy');
        $genres[] = array('short_name' => 'Party', 'name' => 'Party');
        $genres[] = array('short_name' => 'Educational', 'name' => 'Educational');

        
        
        $genres[] = array('short_name' => 'Other', 'name' => 'Other....');

        return $genres;
    }

}
