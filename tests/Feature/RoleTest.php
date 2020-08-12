<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function user_profile_page_shows_global_roles()
    {
        $user = $this->createUser();
        $admin = $this->createAdminUser();

        $response = $this->actingAs($admin)->get(route('profile', $user->id));
        $response->assertSee("Admin");
    }

    /** @test */
    public function add_roles_only_shows_for_admins()
    {
        $user = $this->createUser();
        $admin = $this->createAdminUser();

        $response = $this->actingAs($user)->get(route('profile', $user->id));
        $response->assertDontSee(__('user.add_role'));

        $response = $this->actingAs($admin)->get(route('profile', $user->id));
        $response->assertSee(__('user.add_role'));

    }

    /** User's role tags show in profile */

    /** Guests cannot see user roles */

    /** @test */
    public function user_is_added_to_role_from_form()
    {
        $user = $this->createUser();
        $admin = $this->createAdminUser();

        $response = $this->actingAs($admin)->post(route('add-role', $user->id), ['role' => '1']);
        
        $data['user_id'] = $user->id;
        $data['role_id'] = 1;

        $this->assertDatabaseHas('user_role', $data);

        $response->assertRedirect(route('profile', $user->id));
    }

    /** Adding a role checks for if the user is already in that role */

    /** Adding a role checks for admin permission */

    /** Admin cannot remove admin from himself */

    /** Removing role checks for admin permissions */

    /** Removing roles works */
    /** @test */
    public function removing_user_from_role_works()
    {
        $this->withoutExceptionHandling();

        $user = $this->createAdminUser();
        $admin = $this->createAdminUser();

        $response = $this->actingAs($admin)->get(route('remove-role', ['id' => $user->id, 'role' => 1]));

        $response->assertStatus(200);
        $response->assertViewIs('site.confirm');

        $See[] = $user->name;
        $See[] = __('user.confirm_remove_button');

        $response->assertSeeInOrder($See);

        $response = $this->actingAs($admin)->post(route('remove-role', ['id' => $user->id, 'role' => 1]), ['confirm' => true]);

        $data['user_id'] = $user->id;
        $data['role_id'] = 1;

        $this->assertDatabaseMissing('user_role', $data);

        $response->assertRedirect(route('profile', $user->id));
    }

}
