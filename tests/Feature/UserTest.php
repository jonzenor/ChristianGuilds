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

}
