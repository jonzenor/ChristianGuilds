<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function user_profile_page_loads()
    {
        $user = $this->createUser();
        $response = $this->get(route('profile', $user->id));

        $response->assertStatus(200);
        $response->assertViewIs('user.show');
    }

    /** @test */
    public function user_name_shows_on_profile_page()
    {
        $user = $this->createUser();
        $response = $this->get(route('profile', $user->id));

        $response->assertSee($user->name);
    }

    /** @test */
    public function user_show_verifies_user_exists()
    {
        $response = $this->followingRedirects()->get(route('profile', 25));

        $response->assertSee(__('user.invalid_user'));
    }

    /** @test */
    public function user_edit_page_loads()
    {
        $this->withoutExceptionHandling();

        $user = $this->createUser();
        $response = $this->actingAs($user)->get(route('profile-edit', $user->id));

        $response->assertStatus(200);
        $response->assertViewIs('user.edit');
    }

    /** @test */
    public function user_profile_saves_name()
    {
        $user = $this->createUser();
        $response = $this->actingAs($user)->post(route('profile-update', $user->id), ['name' => 'Test2']);

        $data['id'] = $user->id;
        $data['name'] = "Test2";

        $this->assertDatabaseHas('users', $data);
    }

    /** @test */
    public function user_cannot_edit_another_user()
    {
        $user = $this->createUser();
        $profile = $this->createUser();

        $response = $this->actingAs($user)->followingRedirects()->get(route('profile-edit', $profile->id));

        $response->assertStatus(404);
    }

    /**  */
    public function user_pushover_key_shows_on_profile()
    {
        $this->withoutMiddleware();

        $user = $this->createUser();
        $response = $this->actingAs($user)->get(route('profile', $user->id));

        $response->assertSee(__('user.pushover_key'));
    }

}
