<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PermTest extends TestCase
{

    use DatabaseMigrations;

    private $admin;
    private $user;
    private $user2;
    private $guild;
    private $gameMaster;
    private $guildMaster;
    private $communityManager;

    public function setUp(): void
    {
        parent::setUp();

        $this->runDatabaseMigrations();

        $this->admin = $this->createAdminUser();
        $this->user = $this->createUser();
        $this->user2 = $this->createUser();
        $this->guild = $this->createGuild($this->user2);
        $this->gameMaster = $this->createGameMasterUser();
        $this->guildMaster = $this->createGuildMasterUser();
        $this->communityManager = $this->createCommunityManagerUser();
    }

    //**********************//
    // ACP Index Page Test //
    //********************//
    
    /** @test */
    public function admin_can_access_acp()
    {
        $response = $this->actingAs($this->admin)->get('/acp');
        $response->assertStatus(200);
    }

    /** @test */
    public function guild_master_can_access_acp()
    {
        $response = $this->actingAs($this->guildMaster)->get('/acp');
        $response->assertStatus(200);
    
    }
    /** @test */
    public function game_master_can_access_acp()
    {
        $response = $this->actingAs($this->gameMaster)->get('/acp');
        $response->assertStatus(200);
    }

    /** @test */
    public function community_manager_can_access_acp()
    {
        $response = $this->actingAs($this->communityManager)->get('/acp');
        $response->assertStatus(200);
    }

    /** @test */
    public function users_cannot_access_acp()
    {
        $response = $this->actingAs($this->user)->get('/acp');
        $response->assertStatus(404);
    }

    /** @test */
    public function guests_cannot_access_acp()
    {
        $response = $this->get('/acp');
        $response->assertRedirect('login');
    }
    

    //*******************//
    // Admin Pages Test //
    //*****************//
    
    /** 
     * @test 
     * @dataProvider adminOnlyPageList
     */
    public function verify_admin_can_access_admin_pages($adminPage)
    {
        $adminPage = $this->replaceIDs($adminPage);
        $response = $this->actingAs($this->admin)->get($adminPage);
        $response->assertStatus(200);
    }

    /** 
     * @test 
     * @dataProvider adminOnlyPageList
     */
    public function verify_game_masters_cannot_access_admin_pages($adminPage)
    {
        $adminPage = $this->replaceIDs($adminPage);
        $response = $this->actingAs($this->gameMaster)->get($adminPage);
        $response->assertStatus(404);
    }

    /** 
     * @test 
     * @dataProvider adminOnlyPageList
     */
    public function verify_guild_masters_cannot_access_admin_pages($adminPage)
    {
        $adminPage = $this->replaceIDs($adminPage);
        $response = $this->actingAs($this->guildMaster)->get($adminPage);
        $response->assertStatus(404);
    }

    /** 
     * @test 
     * @dataProvider adminOnlyPageList
     */
    public function verify_community_managers_cannot_access_admin_pages($adminPage)
    {
        $adminPage = $this->replaceIDs($adminPage);
        $response = $this->actingAs($this->communityManager)->get($adminPage);
        $response->assertStatus(404);
    }

    /** 
     * @test 
     * @dataProvider adminOnlyPageList
     */
    public function verify_standard_user_cannot_access_admin_pages($adminPage)
    {
        $adminPage = $this->replaceIDs($adminPage);
        $response = $this->actingAs($this->user)->get($adminPage);
        $response->assertStatus(404);
    }

    /**
     * @test
     * @dataProvider adminOnlyPageList
     */
    public function verify_guests_cannot_access_admin_pages($adminPage)
    {
        $adminPage = $this->replaceIDs($adminPage);
        $response = $this->get($adminPage);
        $response->assertRedirect('login');
    }

    //*************************//
    // Game Master Pages Test //
    //***********************//
    
    /** 
     * @test 
     * @dataProvider gameMasterOnlyPageList
     */
    public function verify_admin_can_access_game_master_pages($adminPage)
    {
        $adminPage = $this->replaceIDs($adminPage);
        $response = $this->actingAs($this->admin)->get($adminPage);
        $response->assertStatus(200);
    }

    /** 
     * @test 
     * @dataProvider gameMasterOnlyPageList
     */
    public function verify_game_masters_can_access_game_master_pages($adminPage)
    {
        $adminPage = $this->replaceIDs($adminPage);
        $response = $this->actingAs($this->gameMaster)->get($adminPage);
        $response->assertStatus(200);
    }

    /** 
     * @test 
     * @dataProvider gameMasterOnlyPageList
     */
    public function verify_guild_masters_cannot_access_game_master_pages($adminPage)
    {
        $adminPage = $this->replaceIDs($adminPage);
        $response = $this->actingAs($this->guildMaster)->get($adminPage);
        $response->assertStatus(404);
    }

    /** 
     * @test 
     * @dataProvider gameMasterOnlyPageList
     */
    public function verify_community_managers_cannot_access_game_master_pages($adminPage)
    {
        $adminPage = $this->replaceIDs($adminPage);
        $response = $this->actingAs($this->communityManager)->get($adminPage);
        $response->assertStatus(404);
    }

    /** 
     * @test 
     * @dataProvider gameMasterOnlyPageList
     */
    public function verify_standard_user_cannot_access_game_master_pages($adminPage)
    {
        $adminPage = $this->replaceIDs($adminPage);
        $response = $this->actingAs($this->user)->get($adminPage);
        $response->assertStatus(404);
    }

    /**
     * @test
     * @dataProvider gameMasterOnlyPageList
     */
    public function verify_guests_cannot_access_game_master_pages($adminPage)
    {
        $adminPage = $this->replaceIDs($adminPage);
        $response = $this->get($adminPage);
        $response->assertRedirect('login');
    }

    //**************************//
    // Guild Master Pages Test //
    //************************//
    
    /** 
     * @test 
     * @dataProvider guildMasterOnlyPageList
     */
    public function verify_admin_can_access_guild_master_pages($adminPage)
    {
        $adminPage = $this->replaceIDs($adminPage);
        $response = $this->actingAs($this->admin)->get($adminPage);
        $response->assertStatus(200);
    }

    /** 
     * @test 
     * @dataProvider guildMasterOnlyPageList
     */
    public function verify_game_masters_cannot_access_guild_master_pages($adminPage)
    {
        $adminPage = $this->replaceIDs($adminPage);
        $response = $this->actingAs($this->gameMaster)->get($adminPage);
        $response->assertStatus(404);
    }

    /** 
     * @test 
     * @dataProvider guildMasterOnlyPageList
     */
    public function verify_guild_masters_can_access_guild_master_pages($adminPage)
    {
        $adminPage = $this->replaceIDs($adminPage);
        $response = $this->actingAs($this->guildMaster)->get($adminPage);
        $response->assertStatus(200);
    }

    /** 
     * @test 
     * @dataProvider guildMasterOnlyPageList
     */
    public function verify_community_managers_cannot_access_guild_master_pages($adminPage)
    {
        $adminPage = $this->replaceIDs($adminPage);
        $response = $this->actingAs($this->communityManager)->get($adminPage);
        $response->assertStatus(404);
    }

    /** 
     * @test 
     * @dataProvider guildMasterOnlyPageList
     */
    public function verify_standard_user_cannot_access_guild_master_pages($adminPage)
    {
        $adminPage = $this->replaceIDs($adminPage);
        $response = $this->actingAs($this->user)->get($adminPage);
        $response->assertStatus(404);
    }

    /**
     * @test
     * @dataProvider guildMasterOnlyPageList
     */
    public function verify_guests_cannot_access_guild_master_pages($adminPage)
    {
        $adminPage = $this->replaceIDs($adminPage);
        $response = $this->get($adminPage);
        $response->assertRedirect('login');
    }

    //*******************************//
    // Community Manager Pages Test //
    //*****************************//
    
    /** 
     * @dataProvider communityManagerOnlyPageList
     */
    public function verify_admin_can_access_community_manager_pages($adminPage)
    {
        $adminPage = $this->replaceIDs($adminPage);
        $response = $this->actingAs($this->admin)->get($adminPage);
        $response->assertStatus(200);
    }

    /** 
     * @dataProvider communityManagerOnlyPageList
     */
    public function verify_game_masters_cannot_access_community_manager_pages($adminPage)
    {
        $adminPage = $this->replaceIDs($adminPage);
        $response = $this->actingAs($this->gameMaster)->get($adminPage);
        $response->assertStatus(404);
    }

    /** 
     * @dataProvider communityManagerOnlyPageList
     */
    public function verify_guild_masters_cannot_access_community_manager_pages($adminPage)
    {
        $adminPage = $this->replaceIDs($adminPage);
        $response = $this->actingAs($this->guildMaster)->get($adminPage);
        $response->assertStatus(404);
    }

    /** 
     * @dataProvider communityManagerOnlyPageList
     */
    public function verify_community_managers_can_access_community_manager_pages($adminPage)
    {
        $adminPage = $this->replaceIDs($adminPage);
        $response = $this->actingAs($this->communityManager)->get($adminPage);
        $response->assertStatus(200);
    }

    /** 
     * @dataProvider communityManagerOnlyPageList
     */
    public function verify_standard_user_cannot_access_community_manager_pages($adminPage)
    {
        $adminPage = $this->replaceIDs($adminPage);
        $response = $this->actingAs($this->user)->get($adminPage);
        $response->assertStatus(404);
    }

    /**
     * @dataProvider communityManagerOnlyPageList
     */
    public function verify_guests_cannot_access_community_manager_pages($adminPage)
    {
        $adminPage = $this->replaceIDs($adminPage);
        $response = $this->get($adminPage);
        $response->assertRedirect('login');
    }

    //*********************************//
    // Functions to help with testing //
    //*******************************//

    public function replaceIDs($string)
    {
        $string = str_replace("{user}", $this->user2->id, $string);
        $string = str_replace("{game}", '3', $string);
        $string = str_replace("{genre}", '4', $string);
        $string = str_replace("{guild}", $this->guild->id, $string);

        return $string;
    }

    public function adminOnlyPageList()
    {
        return [
            ['/acp/users'],
            ['/acp/roles'],

            ['/profile/{user}/edit'],
        ];
    }

    public function guildMasterOnlyPageList()
    {
        return [
            ['/acp/guilds'],
            ['/acp/communities'],

            ['/guild/{guild}/edit'],
        ];
    }
    
    public function gameMasterOnlyPageList()
    {
        return [
            ['/acp/games'],
            ['/acp/genres'],

            ['/game/{game}/edit'],
            ['/acp/genre/{genre}/edit']
        ];
    }

    public function communityManagerOnlyPageList()
    {
        return [];
    }
}
