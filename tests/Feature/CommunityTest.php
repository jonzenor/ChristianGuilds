<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommunityTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    // Create community page works

    /** @test */
    public function edit_community_page_loads()
    {
        $this->withoutExceptionHandling();
        $user = $this->createUser();
        $community = $this->createCommunity($user);

        $response = $this->actingAs($user)->get(route('community-edit', $community->id));

        $response->assertStatus(200);
    }

    /** @test */
    public function edit_community_page_saves()
    {
        $user = $this->createUser();
        $community = $this->createCommunity($user);

        $data['name'] = 'My Test Updater Community';
        $data['description'] = "This is a cool description. Join our community!";

        $response = $this->actingAs($user)->post(route('community-update', $community->id), $data);

        $this->assertDatabaseHas('communities', $data);
    }
    
}
