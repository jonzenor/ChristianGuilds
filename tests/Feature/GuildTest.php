<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class GuildTest extends TestCase
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

    // Guild Create page loads
    /** @test */
    public function guild_create_page_loads()
    {   
        $this->withoutExceptionHandling();
        $user = $this->createUser();

        $response = $this->actingAs($user)->get(route('guild-create'));

        $response->assertStatus(200);
        $response->assertViewIs('guild.create');
    }

    // Guild Create Page creates guild

    // User is added to the members table when creating a guild

    /** @test */
    public function guild_shows_in_acp_panel()
    {
        $admin = $this->createAdminUser();
        $guild = $this->createGuild($admin);

        $response = $this->actingAs($admin)->get(route('acp'));

        $response->assertStatus(200);
        $response->assertSee($guild->name);
    }

    /** @test */
    public function guild_list_page_loads()
    {
        $admin = $this->createAdminUser();
        
        $response = $this->actingAs($admin)->get(route('guild-list'));

        $response->assertStatus(200);
        $response->assertViewIs('guild.index');
    }

    /** @test */
    public function users_cannot_access_guild_list()
    {
        $user = $this->createUser();

        $response = $this->actingAs($user)->get(route('guild-list'));
        $response->assertRedirect(route('home'));

        $response = $this->actingAs($user)->followingRedirects()->get(route('guild-list'));
        $response->assertSee(__('site.permission_denied'));
    }

    /** @test */
    public function guild_page_loads()
    {
        $user = $this->createUser();
        $guild = $this->createGuild($user);

        $response = $this->get(route('guild', $guild->id));

        $response->assertStatus(200);
        $response->assertViewIs('guild.show');
    }

    /** @test */
    public function guild_page_shows_if_game_is_pending()
    {
        $game = $this->createGame();
        $game = \App\Game::find($game->id);
        $game->status = 'pending';
        $game->save();

        $user = $this->createUser();
        $guild = $this->createGuild($user);
        $guild = \App\Guild::find($guild->id);
        $guild->game_id = $game->id;
        $guild->save();

        $response = $this->get(route('guild', $guild->id));
        $response->assertSee(__('game.is_pending'));
    }

    /** @test */
    public function guild_edit_page_loads()
    {
        $this->withoutExceptionHandling();

        $user = $this->createUser();
        $guild = $this->createGuild($user);

        $response = $this->actingAS($user)->get(route('guild-edit', $guild->id));

        $response->assertStatus(200);
        $response->assertViewIs('guild.edit');
    }

    // Pending games show on the ACP page

    // Pending games show in the pending games page

    // Create guild with new game works

    // Guild public page is not public until the game is approved (Maybe ??)

    // Game Manager can change the guild's game

    // Game Manager can approve the game

    // Users can edit the guild information

    // Admins can edit the guild information

    // Guests cannot load the guild create page

    // users cannot edit a guild they are not owner of

    // Guild public page loads for all users

}
