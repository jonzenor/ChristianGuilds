<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class GameTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /** @test */
    public function game_count_shows_on_ACP_index()
    {
        DB::table('games')->truncate();
        $this->createGame();
        $this->createGame();

        $admin = $this->createAdminUser();

        $response = $this->actingAs($admin)->get(route('acp'));

        $response->assertStatus(200);
        $response->assertSee(__('game.count', ['count' => 2]));
    }

    // Make sure game page loads with game name
    /** @test */
    public function game_acp_page_loads()
    {
        $this->withoutExceptionHandling();

        $this->createGenre();
        
        DB::table('games')->truncate();
        $game = $this->createGame();

        $admin = $this->createAdminUser();

        $response = $this->actingAs($admin)->get(route('game-list'));

        $response->assertStatus(200);
        $response->assertViewIs('game.index');
        $response->assertSee($game->name);
    }

    // Make sure genre count shows up on ACP

    // Make sure genre page loads with genre name

    // Make sure game edit page loads

    // Make sure game edit page saves

    // Make sure genre edit page loads

    // Make sure genre edit page saves

    // Make sure game add page loads

    // Make sure game add page works

    // Make sure genre add page loads

    // Make sure genre add page works

    // Make sure users cannot access game pages

    // Make sure users cannot access genre pages
}
