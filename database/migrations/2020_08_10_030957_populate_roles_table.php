<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PopulateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('roles')->insert([
            ['name' => 'Admin', 'color' => "red-700", 'context' => 'global'],
            ['name' => 'Game Master', 'color' => 'green-700', 'context' => 'global'],
            ['name' => 'Community Manager', 'color' => 'blue-700', 'context' => 'global'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('roles')->where('name', '=', 'Admin')->delete();
        DB::table('roles')->where('name', '=', 'Game Masger')->delete();
        DB::table('roles')->where('name', '=', 'Community Manager')->delete();
    }
}
