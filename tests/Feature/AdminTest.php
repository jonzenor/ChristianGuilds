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
        $response = $this->get('/acp');

        $response->assertStatus(200);
        $response->assertViewIs('acp.index');
    }

    /** Guest users cannot access acp */

    /** Normal users cannot access acp */

    /** Admin users can access acp */

    /** Users show in the ACP widget */
    /** @test */
    public function users_show_in_acp_widget()
    {
        $user = $this->createUser();

        $response = $this->get('/acp');
        $response->assertSee($user->name);
    }

    /** @test */
    public function user_count_shows_in_acp_widget()
    {
        $this->createUser();
        $this->createUser();
        $this->createUser();

        $response = $this->get('/acp');
        $response->assertSee(__('user.count', ['count' => '3']));
    }
}
