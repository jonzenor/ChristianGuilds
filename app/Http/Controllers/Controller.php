<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Cache;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function getUser($id) 
    {
        return Cache::rememberForever('User:' . $id, function() use($id) {
            return User::find($id);
        });
    }

    public function getUsers()
    {
        return Cache::rememberForever('Users', function() {
            return User::all();
        });
    }

    public function getUserCount()
    {
        return Cache::rememberForever('Users:count', function() {
            return User::all()->count();
        });
    }

}

