<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_page_loads()
    {
        $this->withoutMiddleware();
        $this->withoutExceptionHandling();

        $admin = $this->createAdminUser();
        $response = $this->actingAs($admin)->get('/acp');

        $response->assertStatus(200);
        $response->assertViewIs('acp.index');
    }

    /** @test */
    public function guests_cannot_load_acp()
    {
        $response = $this->get('/acp');

        $response->assertRedirect('login');
    }

    /** @test */
    public function users_cannot_load_acp()
    {
        $this->withoutMiddleware();
        $user = $this->createUser();

        $response = $this->actingAs($user)->get(route('acp'));
        $response->assertStatus(404);

        $response = $this->actingAs($user)->followingRedirects()->get(route('acp'));
        $response->assertStatus(404);
    }

    /** @test */
    public function users_show_in_acp_widget()
    {
        $this->withoutMiddleware();
        $user = $this->createUser();
        $admin = $this->createAdminUser();

        $response = $this->actingAs($admin)->get('/acp');
        $response->assertSee($user->name);
    }

    /** @test */
    public function user_count_shows_in_acp_widget()
    {
        $this->withoutMiddleware();
        $this->createUser();
        $this->createUser();
        $this->createUser();
        $admin = $this->createAdminUser();


        $response = $this->actingAs($admin)->get('/acp');
        $response->assertSee(__('user.count', ['count' => '4']));
    }

    /** @test */
    public function user_profile_links_from_acp_user_widget()
    {
        $this->withoutMiddleware();
        $user = $this->createUser();
        $admin = $this->createAdminUser();

        $response = $this->actingAs($admin)->get('/acp');
        $response->assertSee(route('profile', $user->id));
    }

    /** @test */
    public function acp_user_list_page_loads()
    {
        $this->withoutMiddleware();
        $user = $this->createUser();
        $admin = $this->createAdminUser();

        $response = $this->actingAs($admin)->get(route('user-list'));
        $response->assertStatus(200);
        $response->assertViewIs('user.index');
        $response->assertSee($user->name);
    }

    /** @test */
    public function users_cannot_view_user_list_page()
    {
        $this->withoutMiddleware();
        $user = $this->createUser();

        $response = $this->actingAs($user)->get(route('user-list'));
        $response->assertStatus(404);

        $response = $this->actingAs($user)->followingRedirects()->get(route('user-list'));
        $response->assertStatus(404);
    }

    /** @test */
    public function roles_show_in_acp()
    {
        $this->withoutMiddleware();
        $admin = $this->createAdminUser();

        $response = $this->actingAs($admin)->get(route('acp'));
        $response->assertSee('Admin');
        $response->assertSee('Game Master');
        $response->assertSee('Community Manager');
    }
}
