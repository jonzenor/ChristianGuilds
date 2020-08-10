<?php

namespace Tests;

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


}
