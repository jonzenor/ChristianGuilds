<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class Page404Test extends TestCase
{
    use RefreshDatabase;
    
    /**
     * @test
     * @dataProvider pageList
     */
    public function verify_guests_cannot_access_restricted_pages($page)
    {
        $admin = $this->createAdminUser();

        $page = $this->replaceIDs($page);
        $response = $this->actingAs($admin)->get($page);
        $response->assertStatus(404);
    }

    public function replaceIDs($string)
    {
        $string = str_replace("{user}", '999', $string);
        $string = str_replace("{game}", '99999', $string);
        $string = str_replace("{genre}", '999', $string);
        $string = str_replace("{guild}", '999999999999', $string);
        $string = str_replace("{community}", '9999', $string);

        return $string;
    }


    public function pageList()
    {
        return [
            ['/guild/{guild}'],
            ['/guild/{guild}/edit'],
            ['/guild/{guild}/apps'],

            ['/community/{community}'],
            ['/community/{community}/edit'],
            //['/community/{community}/apps'],

        ];
    }
}
