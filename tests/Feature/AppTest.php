<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AppTest extends TestCase
{
    use DatabaseMigrations;

    private $user;
    private $guildmanager;
    private $admin;
    private $guild;
    private $application;

    public function setup(): void
    {
        parent::setUp();

        $this->runDatabaseMigrations();

        $this->admin = $this->createAdminUser();
        $this->user = $this->createUser();
        $this->guildmanager = $this->createUser();
        $this->guild = $this->createGuild($this->guildmanager);
        $this->application = $this->createGuildApplication($this->guild, "guild", "public");
        $this->privateApplication = $this->createGuildApplication($this->guild, "guild", "private");
    }

    /** @test */
    public function adding_question_to_app_saves()
    {
        $data['text'] = "Did this question save?";
        $response = $this->actingAs($this->admin)->post(route('app-question-add', $this->application->id), $data);
        $this->assertDatabaseHas('questions', $data);
    }
    
    /** @test */
    public function adding_question_to_app_returns_to_app_manage()
    {
        $data['text'] = "This is a test question?";
        $response = $this->actingAs($this->admin)->post(route('app-question-add', $this->application->id), $data);
        $response->assertLocation(route('app-manage', $this->application->id));
    }

    /** @test */
    public function questions_show_on_app_manage_page()
    {
        $data['text'] = "Do you see me?";
        $response = $this->actingAs($this->admin)->followingRedirects()->post(route('app-question-add', $this->application->id), $data);
        $response->assertSee($data['text']);
    }

    /** @test */
    public function edit_app_page_loads()
    {
        $response = $this->actingAs($this->guildmanager)->get(route('app-edit', $this->application->id));
        $response->assertStatus(200);
        $response->assertViewIs('guild.app.edit');
    }

    /** @test */
    public function update_app_page_saves_data()
    {
        $data['name'] = "This app has been updated";
        $data['visibility'] = 'public';
        $data['promote_to'] = '1';
        $response = $this->actingAs($this->guildmanager)->post(route('app-update', $this->application->id), $data);

        $data['title'] = $data['name'];
        $data['promotion_rank'] = $data['promote_to'];
        unset($data['name']);
        unset($data['promote_to']);

        $this->assertDatabaseHas('apps', $data);
    }
    
    /** @test */
    public function update_app_returns_to_app_manage_page()
    {
        $data['name'] = "This app has been updated";
        $data['visibility'] = 'public';
        $data['promote_to'] = '1';
        $response = $this->actingAs($this->guildmanager)->post(route('app-update', $this->application->id), $data);
        $response->assertLocation(route('app-manage', $this->application->id));
    }

    /** @test */
    public function public_apps_show_in_sidebar()
    {
        $response = $this->actingAs($this->guildmanager)->get(route('guild', $this->guild->id));
        $response->assertSee($this->application->title);
    }

    /** @test */
    public function private_apps_do_now_show_in_sidebar()
    {
        $response = $this->actingAs($this->guildmanager)->get(route('guild', $this->guild->id));
        $response->assertDontSee($this->privateApplication->title);
    }

    /** @test */
    public function application_submission_page_loads()
    {
        $response = $this->actingAs($this->user)->get(route('app-submit', $this->application->id));
        $response->assertStatus(200);
        $response->assertViewIs('guild.app.submit');
    }

    /** @test */
    public function submitting_an_application_saves()
    {
        $app2 = $this->createGuildApplication($this->guild, 'guild', 'public');
        $qdata['text'] = "What is your favorite color?";
        $this->actingAs($this->guildmanager)->post(route('app-question-add', $app2->id), $qdata);

        $app = \App\App::find($app2->id);
        $data['name'] = "Johnny";
        foreach ($app->questions as $question) {
            $data['question-' . $question->id] = "Some random text.";
        }

        $this->actingAs($this->guildmanager)->post(route('app-submit-answers', $app2->id), $data);
        $this->assertDatabaseCount('submissions', 1);
    }

    /** @test */
    public function user_can_view_their_own_submission()
    {
        $userNew = $this->createUser();
        $submission = $this->submitGuildApplication($this->application, $userNew);

        $response = $this->actingAs($userNew)->get(route('app-submission', $submission->id));
        $response->assertStatus(200);
        $response->assertViewIs('guild.app.submission');
    }

    // Test that guild members don't see guild membership apps

    // Test that guild managers don't see guild manager apps

    // User can fill out app

    // Accepting app makes user a member

    // Accepting manager app makes member a manager
}
