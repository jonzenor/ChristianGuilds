<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class RoleTest extends TestCase
{

    use RefreshDatabase;
    use WithoutMiddleware;

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

    /** @test */
    public function user_is_added_to_role_from_form()
    {
        $this->withoutMiddleware();

        $user = $this->createUser();
        $admin = $this->createAdminUser();

        $response = $this->actingAs($admin)->post(route('add-role', $user->id), ['role' => '1']);
        
        $data['user_id'] = $user->id;
        $data['role_id'] = 1;

        $this->assertDatabaseHas('user_role', $data);

        $response->assertRedirect(route('profile', $user->id));
    }

    /** @test */
    public function adding_role_gives_success_message()
    {
        $this->withoutMiddleware();

        $user = $this->createUser();
        $admin = $this->createAdminUser();

        $response = $this->actingAs($admin)->followingRedirects()->post(route('add-role', $user->id), ['role' => '1']);
        
        $response->assertSee(__('user.role_add_success'));
    }

    /** @test */
    public function user_cannot_be_added_to_a_duplicate_role()
    {
        $user = $this->createAdminUser();
        $admin = $this->createAdminUser();

        $response = $this->actingAs($admin)->followingRedirects()->post(route('add-role', $user->id), ['role' => '1']);
        
        $data['user_id'] = $user->id;
        $data['role_id'] = 1;

        $response->assertSee(__('user.duplicate_role'));
    }

    /** @test */
    public function make_sure_role_exists()
    {
        $user = $this->createUser();
        $admin = $this->createAdminUser();

        $response = $this->actingAs($admin)->followingRedirects()->post(route('add-role', $user->id), ['role' => '15']);
        
        $response->assertSee(__('user.invalid_role'));
    }

    /** @test */
    public function make_sure_user_exists()
    {
        $admin = $this->createAdminUser();

        $response = $this->actingAs($admin)->followingRedirects()->post(route('add-role', 45), ['role' => '15']);
        
        $response->assertSee(__('user.invalid_user'));
    }

    /** @test */
    public function add_role_verifies_permission()
    {
        $user = $this->createUser();
        $admin = $this->createAdminUser();

        $response = $this->actingAs($user)->followingRedirects()->post(route('add-role', $user->id), ['role' => '1']);
        $response->assertSee(__('site.permission_denied'));
    }

    /** @test */
    public function del_role_verifies_permission()
    {
        $user = $this->createUser();
        $admin = $this->createAdminUser();

        $response = $this->actingAs($user)->followingRedirects()->get(route('remove-role', ['id' => $user->id, 'role' => 1]));
        $response->assertSee(__('site.permission_denied'));
    }
    

    /** Admin cannot remove admin from himself */

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

    /** @test */
    public function removing_user_from_role_gives_message()
    {
        $user = $this->createAdminUser();
        $admin = $this->createAdminUser();

        $response = $this->followingRedirects()->actingAs($admin)->post(route('remove-role', ['id' => $user->id, 'role' => 1]), ['confirm' => true]);
        $response->assertSee(__('user.role_del_success'));
    }

    /** @test */
    public function acp_roles_page_loads()
    {
        $this->withoutExceptionHandling();
        $this->withoutMiddleware();
        $admin = $this->createAdminUser();

        $response = $this->actingAs($admin)->get(route('role-list'));

        $response->assertStatus(200);
        $response->assertViewIs('role.index');
        $response->assertSee('Game Master');
    }

    /** @test */
    public function acp_roles_page_lists_users()
    {
        $this->withoutMiddleware();

        $admin = $this->createAdminUser();

        $response = $this->actingAs($admin)->get(route('role-list'));

        $see[] = 'Admin';
        $see[] = $admin->name;

        $response->assertSeeInOrder($see);
    }

}
