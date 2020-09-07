<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PopulateContactTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('contact_topics')->insert([
            ['name' => 'new_user', 'category' => "admin"],
            ['name' => 'alerts', 'category' => "admin"],
            ['name' => 'new_guild', 'category' => 'admin'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('contact_topics')->where('name', '=', 'new_user')->delete();
        DB::table('contact_topics')->where('name', '=', 'alerts')->delete();
        DB::table('contact_topics')->where('name', '=', 'new_guild')->delete();
    }
}
