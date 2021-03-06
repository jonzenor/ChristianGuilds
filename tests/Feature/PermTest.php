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
    private $userRequesting;
    private $gameMaster;
    private $guildMaster;
    private $communityManager;

    private $guild;
    private $community;
    private $pendingGame;
    private $application;
    private $submission;

    public function setUp(): void
    {
        parent::setUp();

        $this->runDatabaseMigrations();

        $this->admin = $this->createAdminUser();
        $this->user = $this->createUser();
        $this->user2 = $this->createUser();
        $this->userRequesting = $this->createUser();
        $this->gameMaster = $this->createGameMasterUser();
        $this->guildMaster = $this->createGuildMasterUser();
        $this->communityManager = $this->createCommunityManagerUser();
        
        $this->guild = $this->createGuild($this->user2);
        $this->community = $this->createCommunity($this->user2);
        $this->pendingGame = $this->createPendingGame($this->user2);
        $this->application = $this->createGuildApplication($this->guild, "guild", "public");
        $this->submission = $this->submitGuildApplication($this->application, $this->userRequesting);
    }

    //**********************//
    // ACP Index Page Test //
    //********************//
    
    /** 
     * @test 
     * @group acp_test
     **/
    public function admin_can_access_acp()
    {
        $response = $this->actingAs($this->admin)->get('/acp');
        $response->assertStatus(200);
    }

    /** 
     * @test 
     * @group acp_test
     **/
    public function guild_master_can_access_acp()
    {
        $response = $this->actingAs($this->guildMaster)->get('/acp');
        $response->assertStatus(200);
    
    }

    /** 
     * @test 
     * @group acp_test
     **/
    public function game_master_can_access_acp()
    {
        $response = $this->actingAs($this->gameMaster)->get('/acp');
        $response->assertStatus(200);
    }

    /** 
     * @test 
     * @group acp_test
     **/
    public function community_manager_can_access_acp()
    {
        $response = $this->actingAs($this->communityManager)->get('/acp');
        $response->assertStatus(200);
    }

    /** 
     * @test 
     * @group acp_test
     **/
    public function users_cannot_access_acp()
    {
        $response = $this->actingAs($this->user)->get('/acp');
        $response->assertStatus(404);
    }

    /** 
     * @test 
     * @group acp_test
     **/
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
     * @group adminOnly
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
     * @group adminOnly
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
     * @group adminOnly
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
     * @group adminOnly
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
     * @group adminOnly
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
     * @group adminOnly
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
     * @group gameMasterOnly
     * @dataProvider gameMasterOnlyPageList
     */
    public function verify_admin_can_access_game_master_pages($adminPage)
    {
        $this->withoutExceptionHandling();
        $adminPage = $this->replaceIDs($adminPage);
        $response = $this->actingAs($this->admin)->get($adminPage);
        $response->assertStatus(200);
    }

    /** 
     * @test
     * @group gameMasterOnly
     * @dataProvider gameMasterOnlyPageList
     */
    public function verify_game_masters_can_access_game_master_pages($adminPage)
    {
        $this->withoutExceptionHandling();
        $adminPage = $this->replaceIDs($adminPage);
        $response = $this->actingAs($this->gameMaster)->get($adminPage);
        $response->assertStatus(200);
    }

    /** 
     * @test
     * @group gameMasterOnly
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
     * @group gameMasterOnly
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
     * @group gameMasterOnly
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
     * @group gameMasterOnly
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
     * @group guildMasterOnly
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
     * @group guildMasterOnly
     * @dataProvider guildMasterOnlyFormList
     */
    public function verify_admin_can_access_guild_master_forms($adminPage)
    {
        $field = $this->getValidFormField($adminPage);
        $data[$field] = " ";

        $adminPage = $this->replaceIDs($adminPage);
        $response = $this->actingAs($this->admin)->post($adminPage, $data);
        $response->assertSessionHasErrors($field);
    }

    /** 
     * @test
     * @group guildMasterOnly
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
     * @group guildMasterOnly
     * @dataProvider guildMasterOnlyFormList
     */
    public function verify_game_masters_cannot_access_guild_master_forms($adminPage)
    {
        $field = $this->getValidFormField($adminPage);
        $data[$field] = " ";

        $adminPage = $this->replaceIDs($adminPage);
        $response = $this->actingAs($this->gameMaster)->post($adminPage, $data);
        $response->assertStatus(404);
    }

    /** 
     * @test
     * @group guildMasterOnly
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
     * @group guildMasterOnly
     * @dataProvider guildMasterOnlyFormList
     */
    public function verify_guild_masters_can_access_guild_master_forms($adminPage)
    {
        $field = $this->getValidFormField($adminPage);
        $data[$field] = " ";
        
        $adminPage = $this->replaceIDs($adminPage);
        $response = $this->actingAs($this->guildMaster)->post($adminPage, $data);
        $response->assertSessionHasErrors($field);
    }

    /** 
     * @test
     * @group guildMasterOnly
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
     * @group guildMasterOnly
     * @dataProvider guildMasterOnlyFormList
     */
    public function verify_community_managers_cannot_access_guild_master_forms($adminPage)
    {
        $field = $this->getValidFormField($adminPage);
        $data[$field] = "Test Data";

        $adminPage = $this->replaceIDs($adminPage);
        $response = $this->actingAs($this->communityManager)->post($adminPage, $data);
        $response->assertStatus(404);
    }

    /** 
     * @test
     * @group guildMasterOnly
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
     * @group guildMasterOnly
     * @dataProvider guildMasterOnlyFormList
     */
    public function verify_standard_user_cannot_access_guild_master_forms($adminPage)
    {
        $field = $this->getValidFormField($adminPage);
        $data[$field] = "Test Data";

        $adminPage = $this->replaceIDs($adminPage);
        $response = $this->actingAs($this->user)->post($adminPage, $data);
        $response->assertStatus(404);
    }

    /**
     * @test
     * @group guildMasterOnly
     * @dataProvider guildMasterOnlyPageList
     */
    public function verify_guests_cannot_access_guild_master_pages($adminPage)
    {
        $adminPage = $this->replaceIDs($adminPage);
        $response = $this->get($adminPage);
        $response->assertRedirect('login');
    }

    /**
     * @test
     * @group guildMasterOnly
     * @dataProvider guildMasterOnlyFormList
     */
    public function verify_guests_cannot_access_guild_master_forms($adminPage)
    {
        $field = $this->getValidFormField($adminPage);
        $data[$field] = "Test Data";

        $adminPage = $this->replaceIDs($adminPage);
        $response = $this->post($adminPage, $data);
        $response->assertRedirect('login');
    }


    //*******************************//
    // Community Manager Pages Test //
    //*****************************//
    
    /** 
     * @group communityManagerOnly
     * @dataProvider communityManagerOnlyPageList
     */
    public function verify_admin_can_access_community_manager_pages($adminPage)
    {
        $adminPage = $this->replaceIDs($adminPage);
        $response = $this->actingAs($this->admin)->get($adminPage);
        $response->assertStatus(200);
    }

    /** 
     * @group communityManagerOnly
     * @dataProvider communityManagerOnlyPageList
     */
    public function verify_game_masters_cannot_access_community_manager_pages($adminPage)
    {
        $adminPage = $this->replaceIDs($adminPage);
        $response = $this->actingAs($this->gameMaster)->get($adminPage);
        $response->assertStatus(404);
    }

    /** 
     * @group communityManagerOnly
     * @dataProvider communityManagerOnlyPageList
     */
    public function verify_guild_masters_cannot_access_community_manager_pages($adminPage)
    {
        $adminPage = $this->replaceIDs($adminPage);
        $response = $this->actingAs($this->guildMaster)->get($adminPage);
        $response->assertStatus(404);
    }

    /** 
     * @group communityManagerOnly
     * @dataProvider communityManagerOnlyPageList
     */
    public function verify_community_managers_can_access_community_manager_pages($adminPage)
    {
        $adminPage = $this->replaceIDs($adminPage);
        $response = $this->actingAs($this->communityManager)->get($adminPage);
        $response->assertStatus(200);
    }

    /** 
     * @group communityManagerOnly
     * @dataProvider communityManagerOnlyPageList
     */
    public function verify_standard_user_cannot_access_community_manager_pages($adminPage)
    {
        $adminPage = $this->replaceIDs($adminPage);
        $response = $this->actingAs($this->user)->get($adminPage);
        $response->assertStatus(404);
    }

    /** 
     * @group communityManagerOnly
     * @dataProvider communityManagerOnlyPageList
     */
    public function verify_guests_cannot_access_community_manager_pages($adminPage)
    {
        $adminPage = $this->replaceIDs($adminPage);
        $response = $this->get($adminPage);
        $response->assertRedirect('login');
    }

    //*************************************//
    // Ensure Public Pages Are Accessible //
    //***********************************//

    /**
     * @test
     * @group publicPages
     * @dataProvider publicPageList
     */
    public function verify_guests_can_access_public_pages($adminPage)
    {
        $adminPage = $this->replaceIDs($adminPage);
        $response = $this->get($adminPage);
        $response->assertStatus(200);
    }

    /**
     * @test
     * @group publicPages
     * @dataProvider publicPageList
     */
    public function verify_users_can_access_public_pages($adminPage)
    {
        $adminPage = $this->replaceIDs($adminPage);
        $response = $this->actingas($this->user)->get($adminPage);
        $response->assertStatus(200);
    }

    //**********************************************//
    // Ensure Login Protected Pages Are Accessible //
    //********************************************//

    /**
     * @test
     * @group restrictedPages
     * @dataProvider restrictedPageList
     */
    public function verify_guests_cannot_access_restricted_pages($adminPage)
    {
        $adminPage = $this->replaceIDs($adminPage);
        $response = $this->get($adminPage);
        $response->assertRedirect('login');
    }

    /**
     * @test
     * @group restrictedPages
     * @dataProvider restrictedFormList
     */
    public function verify_guests_cannot_access_restricted_forms($adminPage)
    {
        $field = $this->getValidFormField($adminPage);
        $data[$field] = "Test Data";

        $adminPage = $this->replaceIDs($adminPage);
        $response = $this->post($adminPage, $data);
        $response->assertRedirect('login');
    }

    /**
     * @test
     * @group restrictedPages
     * @dataProvider restrictedPageList
     */
    public function verify_users_can_access_restricted_pages($adminPage)
    {
        $adminPage = $this->replaceIDs($adminPage);
        $response = $this->actingas($this->user)->get($adminPage);
        $response->assertStatus(200);
    }

    /** 
     * @test
     * @group restrictedPages
     * @dataProvider restrictedFormList
     */
    public function verify_users_can_access_restricted_forms($adminPage)
    {
        $field = $this->getValidFormField($adminPage);
        $data[$field] = " ";

        $adminPage = $this->replaceIDs($adminPage);
        $response = $this->actingAs($this->admin)->post($adminPage, $data);
        $response->assertSessionHasErrors($field);
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
        $string = str_replace("{community}", $this->community->id, $string);
        $string = str_replace("{app}", $this->application->id, $string);
        $string = str_replace("{submission}", $this->submission->id, $string);

        return $string;
    }

    public function getValidFormField($string)
    {
        if (strpos($string, 'application/{app}/question/add')) {
            return 'text';
        }

        if (strpos($string, 'application/{app}/submit')) {
            return 'name';
        }

        return 'name';
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
            ['/guild/{guild}/apps'],
            ['/guild/{guild}/app/create'],

            ['/application/{app}/manage'],
            ['/application/{app}/edit'],
            ['/application/submission/{submission}'],
            
            ['/community/{community}/edit'],
        ];
    }

    public function guildMasterOnlyFormList()
    {
        return [
            ['/guild/{guild}/app/create'],
            ['/application/{app}/question/add'],
            ['/application/{app}/update'],
        ];
    }
    
    public function gameMasterOnlyPageList()
    {
        return [
            ['/acp/games'],
            ['/acp/genres'],

            ['/game/{game}/edit'],
            ['/acp/games/pending'],

            ['/acp/genre/{genre}/edit'],
            ['/acp/genre/add'],
        ];
    }

    public function communityManagerOnlyPageList()
    {
        return [];
    }

    public function publicPageList()
    {
        return [
            ['/'],
            ['/search'],
            ['/profile/{user}'],
            ['/guild/{guild}'],
            ['/community/{community}'],
            ['/game/{game}'],
#            ['/game/genre/{genre}'],
        ];
    }

    // Restricted pages are those that require a user to be logged in
    // i.e. anybody except guest
    public function restrictedPageList()
    {
        return [
            ['/guild/create'],
            ['/application/{app}'],
        ];
    }

    public function restrictedFormList()
    {
        return [
            ['/application/{app}/submit'],
        ];
    }
}
