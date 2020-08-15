<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

use App\Mail\newUser;


class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    private $cache_for = 1440;

    public function getUser($id)
    {
        return Cache::rememberForever('User:' . $id, function () use ($id) {
            return User::find($id);
        });
    }

    public function getUsers()
    {
        return Cache::remember('Users', $this->cache_for, function () {
            return User::all();
        });
    }

    public function getLatestUsers()
    {
        return Cache::remember('Users:Latest', $this->cache_for, function () {
            return User::orderBy('created_at', 'desc')->limit(config('acp.items_limit'))->get();
        });
    }

    public function getPaginatedUsers($page)
    {
        return Cache::remember('Users:page:' . $page, $this->cache_for, function () {
            return User::paginate(config('acp.paginate_users'));
        });
    }

    public function getAdminUsers()
    {
        return Cache::remember('Users:Admins', $this->cache_for, function () {
            $role = Role::where('name', '=', 'Admin')->first();
            return $role->users;
        });
    }

    public function getUserCount()
    {
        return Cache::rememberForever('Users:count', function () {
            return User::all()->count();
        });
    }

    public function getGlobalRoles()
    {
        return Cache::rememberForever('Roles:global', function () {
            return Role::where('context', '=', 'global')->get();
        });
    }

    public function getRole($id)
    {
        return Cache::rememberForever('Role:' . $id, function () use ($id) {
            return Role::find($id);
        });
    }

    public function clearCache($what, $id)
    {
        if ($what == 'user') {
            Cache::forget('User:' . $id);
        }
    }


    //**************************/
    // Send Message Functions //
    //************************/
    public function send_admin_notification($type, $data)
    {
        // Select admins
        $admins = $this->getAdminUsers();

        foreach ($admins as $admin) {
            if ($type == "new_user") {
                Mail::to($admin)->send(new newUser($data));
            }
        }
    }
}
