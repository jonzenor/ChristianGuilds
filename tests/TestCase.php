<?php

namespace Tests;

use App\Answer;
use App\App;
use App\Question;
use App\Submission;
use DB;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function createUser()
    {
        $user = factory(\App\User::class)->create();
        $user->encoded_name = str_replace("'", "&#039;", $user->name);

        return $user;
    }

    public function createAdminUser()
    {
        $user = factory(\App\User::class)->create();
        $admin = \App\Role::where('name', '=', 'Admin')->first();
        $user->roles()->attach($admin->id);

        return $user;
    }

    public function createGameMasterUser()
    {
        $user = factory(\App\User::class)->create();
        $gm = \App\Role::where('name', '=', 'Game Master')->first();
        $user->roles()->attach($gm->id);

        return $user;
    }

    public function createGuildMasterUser()
    {
        $user = factory(\App\User::class)->create();
        $gm = \App\Role::where('name', '=', 'Guild Master')->first();
        $user->roles()->attach($gm->id);

        return $user;
    }

    public function createCommunityManagerUser()
    {
        $user = factory(\App\User::class)->create();
        $gm = \App\Role::where('name', '=', 'Community Manager')->first();
        $user->roles()->attach($gm->id);

        return $user;
    }

    public function createGame()
    {
        $game = factory(\App\Game::class)->create();
        $game->encoded_name = str_replace("'", "&#039;", $game->name);

        return $game;
    }

    public function createPendingGame($user)
    {
        #$game = factory(\App\Game::class)->create();
        $game = new \App\Game;
        $game->status = "pending";
        $game->genre_id = 2;
        $game->name = "Test Pending Game";
        $game->save();

        $guild = $this->createGuild($user);
        $setGuild = \App\Guild::find($guild->id);
        $setGuild->game_id = $game->id;
        $setGuild->save();

        return $game;
    }


    public function createGenre()
    {
        $genre = factory(\App\Genre::class)->create();
        $genre->encoded_name = str_replace("'", "&#039;", $genre->name);

        return $genre;
    }

    public function createGuild($user)
    {
        $guild = factory(\App\Guild::class)->create();
        $guild->owner_id = $user->id;
        $guild->save();
        
        DB::table('guild_members')->insert([
            'user_id' => $user->id,
            'guild_id' => $guild->id,
            'position' => 'owner',
            'title' => 'Test Guild Manager',
        ]);
        
        $guild->encoded_name = str_replace("'", "&#039;", $guild->name);

        return $guild;
    }

    public function createCommunity($user)
    {
        $community = factory(\App\Community::class)->create();
        $community->owner_id = $user->id;
        $community->save();
        
        DB::table('community_members')->insert([
            'user_id' => $user->id,
            'community_id' => $community->id,
            'position' => 'owner',
        ]);
        
        $community->encoded_name = str_replace("'", "&#039;", $community->name);

        return $community;
    }

    public function createGuildApplication($org, $type, $visibility = "public")
    {
        $application = new App();

        $application->title = "Test " . $visibility . " " . $type . " Application";
        $application->org_id = $org->id;
        $application->org_type = $type;
        $application->visibility = $visibility;
        $application->promotion_rank = 1;

        $application->save();

        $question = new Question();
        $question->text = "Is this a test?";
        $question->number = 1;
        $question->app_id = $application->id;
        $question->save();

        return $application;
    }

    public function submitGuildApplication($app, $user)
    {
        $data['name'] = "Johnny";

        $submission = new Submission();
        $submission->app_id = $app->id;
        $submission->user_id = $user->id;
        $submission->name = $data['name'];
        $submission->status = 'pending';
        $submission->save();

        foreach ($app->questions as $question) {
            $answer = new Answer();
            $answer->submission_id = $submission->id;
            $answer->question_id = $question->id;
            $answer->text = "Some Random Answer";
            $answer->save();
        }

        return $submission;
    }
}
