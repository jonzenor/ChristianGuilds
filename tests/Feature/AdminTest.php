<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTest extends TestCase
{
    /** @test */
    public function admin_page_loads()
    {
        $response = $this->get('/acp');

        $response->assertStatus(200);
        $response->assertViewIs('acp.index');
    }
}
