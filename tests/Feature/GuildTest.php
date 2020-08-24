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

    // Guild List shows in ACP index panel

    // Guild List ACP page works

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
