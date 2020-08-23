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

    /** @test */
    public function genre_count_shows_on_ACP_index()
    {
        DB::table('genres')->truncate();
        $this->createGenre();
        $this->createGenre();

        $admin = $this->createAdminUser();

        $response = $this->actingAs($admin)->get(route('acp'));

        $response->assertStatus(200);
        $response->assertSee(__('game.genre_count', ['count' => 2]));
    }

    /** @test */
    public function genre_acp_page_loads()
    {
        $this->withoutExceptionHandling();

        $genre = $this->createGenre();
        
        $admin = $this->createAdminUser();

        $response = $this->actingAs($admin)->get(route('genre-list'));

        $response->assertStatus(200);
        $response->assertViewIs('genre.index');
        $response->assertSee($genre->name);
    }

    /** @test */
    public function load_the_game_add_page()
    {
        $admin = $this->createAdminUser();

        $response = $this->actingAs($admin)->get(route('game-add'));

        $response->assertStatus(200);
        $response->assertViewIs('game.create');
    }

    /** @test */
    public function add_game_form_works()
    {
        $admin = $this->createAdminUser();

        $data['name'] = 'My Test Game';
        $data['genre'] = 1;

        $response = $this->actingAs($admin)->post(route('game-create'), $data);

        $data['genre_id'] = $data['genre'];
        unset($data['genre']);

        $this->assertDatabaseHas('games', $data);        
    }

    /** @test */
    public function game_edit_page_loads()
    {
        $this->withoutExceptionHandling();
        $game = $this->createGame();
        $admin = $this->createAdminUser();

        $response = $this->actingAs($admin)->get(route('game-edit', $game->id));

        $response->assertStatus(200);
        $response->assertViewIs('game.edit');
    }

    /** @test */
    public function edit_game_page_saves()
    {
        $admin = $this->createAdminUser();
        $game = $this->createGame();

        $data['name'] = 'My Test Updater Game';
        $data['genre'] = 2;

        $response = $this->actingAs($admin)->post(route('game-update', $game->id), $data);

        $data['genre_id'] = $data['genre'];
        unset($data['genre']);

        $this->assertDatabaseHas('games', $data);
    }

    /** @test */
    public function genre_edit_page_loads()
    {
        $this->withoutExceptionHandling();
        $genre = $this->createGenre();
        $admin = $this->createAdminUser();

        $response = $this->actingAs($admin)->get(route('genre-edit', $genre->id));

        $response->assertStatus(200);
        $response->assertViewIs('genre.edit');
    }

    /** @test */
    public function edit_genre_page_saves()
    {
        $admin = $this->createAdminUser();
        $genre = $this->createGenre();

        $data['name'] = 'Bestest Genre Ever!';
        $data['short_name'] = "BGE";

        $response = $this->actingAs($admin)->post(route('genre-update', $genre->id), $data);

        $this->assertDatabaseHas('genres', $data);        
    }

    /** @test */
    public function genre_add_page_loads()
    {
        $this->withoutExceptionHandling();
        $admin = $this->createAdminUser();

        $response = $this->actingAs($admin)->get(route('genre-add'));

        $response->assertStatus(200);
        $response->assertViewIs('genre.create');
    }

    /** @test */
    public function add_genre_form_works()
    {
        $admin = $this->createAdminUser();

        $data['name'] = 'My Test Genre';
        $data['short_name'] = 'MTG';

        $this->actingAs($admin)->post(route('genre-create'), $data);

        $this->assertDatabaseHas('genres', $data);
    }

    // Make sure users cannot access game pages
    /** @test */
    public function game_list_page_permissions_work()
    {
        $user = $this->createUser();
        $admin = $this->createAdminUser();

        $response = $this->actingAs($user)->followingRedirects()->get(route('game-list'));
        $response->assertSee(__('site.permission_denied'));
        $response->assertLocation('/');
    }

    /** @test */
    public function game_edit_page_permissions_work()
    {
        $user = $this->createUser();
        $game = $this->createGame();

        $response = $this->actingAs($user)->followingRedirects()->get(route('game-edit', $game->id));
        $response->assertSee(__('site.permission_denied'));
        $response->assertLocation('/');
    }

    /** @test */
    public function game_update_page_permissions_work()
    {
        $user = $this->createUser();
        $game = $this->createGame();

        $data['name'] = 'My Test Updater Game';
        $data['genre'] = 2;

        $response = $this->actingAs($user)->followingRedirects()->post(route('game-update', $game->id), $data);
        $response->assertSee(__('site.permission_denied'));
        $response->assertLocation('/');
    }
    
    /** @test */
    public function game_add_page_permissions_work()
    {
        $user = $this->createUser();

        $data['name'] = 'My Test Updater Game';
        $data['genre'] = 2;

        $response = $this->actingAs($user)->followingRedirects()->get(route('game-add'));
        $response->assertSee(__('site.permission_denied'));
        $response->assertLocation('/');
    }

    /** @test */
    public function game_create_page_permissions_work()
    {
        $user = $this->createUser();

        $data['name'] = 'My Test Updater Game';
        $data['genre'] = 2;

        $response = $this->actingAs($user)->followingRedirects()->post(route('game-create'), $data);
        $response->assertSee(__('site.permission_denied'));
        $response->assertLocation('/');
    }
    
    // Make sure users cannot access genre pages
    /** @test */
    public function genre_list_page_permissions_work()
    {
        $user = $this->createUser();

        $response = $this->actingAs($user)->followingRedirects()->get(route('genre-list'));
        $response->assertSee(__('site.permission_denied'));
        $response->assertLocation('/');
    }

    /** @test */
    public function genre_edit_page_permissions_work()
    {
        $user = $this->createUser();
        $genre = $this->createGenre();

        $response = $this->actingAs($user)->followingRedirects()->get(route('genre-edit', $genre->id));
        $response->assertSee(__('site.permission_denied'));
        $response->assertLocation('/');
    }

    /** @test */
    public function genre_update_page_permissions_work()
    {
        $user = $this->createUser();
        $genre = $this->createGenre();

        $data['name'] = 'My Test Updater Genre';
        $data['short_name'] = 'MTUG';

        $response = $this->actingAs($user)->followingRedirects()->post(route('genre-update', $genre->id), $data);
        $response->assertSee(__('site.permission_denied'));
        $response->assertLocation('/');
    }
    
    /** @test */
    public function genre_add_page_permissions_work()
    {
        $user = $this->createUser();

        $response = $this->actingAs($user)->followingRedirects()->get(route('genre-add'));
        $response->assertSee(__('site.permission_denied'));
        $response->assertLocation('/');
    }

    /** @test */
    public function genre_create_page_permissions_work()
    {
        $user = $this->createUser();

        $data['name'] = 'My Test Updater genre';
        $data['short_name'] = 'MTUG';

        $response = $this->actingAs($user)->followingRedirects()->post(route('genre-create'), $data);
        $response->assertSee(__('site.permission_denied'));
        $response->assertLocation('/');
    }
}
