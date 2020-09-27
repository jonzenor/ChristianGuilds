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
        $response->assertStatus(404);

        $response = $this->actingAs($user)->followingRedirects()->get(route('guild-list'));
        $response->assertStatus(404);
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
    public function guild_page_404_if_not_found()
    {
        $response = $this->get(route('guild', 400));

        $response->assertStatus(404);
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

    /** @test */
    public function guild_edit_page_404_if_not_found()
    {
        $user = $this->createUser();
        $response = $this->actingAs($user)->get(route('guild-edit', 400));

        $response->assertStatus(404);
    }
    
    // Pending games show on the ACP page

    // Pending games show in the pending games page

    // Create guild with new game works

    // Guild public page is not public until the game is approved (Maybe ??)

    // Game Manager can change the guild's game

    // Game Manager can approve the game

    // Guests cannot load the guild create page

    // Guild public page loads for all users

    /** @test */
    public function guild_search_shows_results()
    {
        $this->withoutExceptionHandling();
        $user = $this->createUser();
        $guild = $this->createGuild($user);

        $response = $this->post(route('search'), ['search' => "test"]);

        $response->assertStatus(200);
        $response->assertViewIs('site.search');
        
        // We can't actually get search results back without feeding fake test data to the search engine...
        //$response->assertSee(route('guild', $guild->id));
    }

    /** @test */
    public function users_cannot_see_edit_button_of_another_guild()
    {
        $guildmaster = $this->createUser();
        $guild = $this->createGuild($guildmaster);

        $user = $this->createUser();

        $response = $this->actingAs($user)->get(route('guild', $guild->id));

        $response->assertDontSee(route('guild-edit', $guild->id));
    }

    /** @test */
    public function guild_master_can_load_apps_page()
    {
        $guildmaster = $this->createUser();
        $guild = $this->createGuild($guildmaster);

        $response = $this->actingAs($guildmaster)->get(route('guild-apps', $guild->id));

        $response->assertStatus(200);
        $response->assertViewIs('guild.apps');
    }

    /** @test */
    public function guild_master_can_load_apps_create_page()
    {
        $guildmaster = $this->createUser();
        $guild = $this->createGuild($guildmaster);

        $response = $this->actingAs($guildmaster)->get(route('guild-app-create', $guild->id));
        $response->assertStatus(200);
        $response->assertViewIs('guild.app.create');
    }

    /** @test */
    public function app_creation_page_saves_app()
    {
        $guildmaster = $this->createUser();
        $guild = $this->createGuild($guildmaster);

        $data['name'] = 'Test App';
        $data['promote_to'] = '1';
        $data['visibility'] = 'public';

        $response = $this->actingAs($guildmaster)->post(route('guild-app-create', $guild->id), $data);

        $data['title'] = $data['name'];
        $data['promotion_rank'] = $data['promote_to'];
        unset($data['promote_to']);
        unset($data['name']);        

        $this->assertDatabaseHas('apps', $data);
    }
}
