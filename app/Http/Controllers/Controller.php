<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Cache;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private $cache_for = 1440;

    public function getUser($id) 
    {
        return Cache::rememberForever('User:' . $id, function() use($id) {
            return User::find($id);
        });
    }

    public function getUsers()
    {
        return Cache::remember('Users', $this->cache_for, function() {
            return User::all();
        });
    }

    public function getUserCount()
    {
        return Cache::rememberForever('Users:count', function() {
            return User::all()->count();
        });
    }

    public function getGlobalRoles()
    {
        return Cache::rememberForever('Roles:global', function() {
            return Role::where('context', '=', 'global')->get();
        });
    }

    public function getRole($id)
    {
        return Cache::rememberForever('Role:' . $id, function() use($id) {
            return Role::find($id);
        });
    }

}

