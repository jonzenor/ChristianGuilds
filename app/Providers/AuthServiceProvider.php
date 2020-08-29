<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use App\Role;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    private $cache_time = 1440;

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('is-self', function ($user, $profile) {
            if ($user->id == $profile->id) {
                return true;
            }
            return false;
        });

        Gate::define('view-acp', function ($user) {
            if ($this->isAdmin($user)) {
                return true;
            }

            if ($this->isGameMaster($user)) {
                return true;
            }

            return false;
        });

        Gate::define('add-global-role', function ($user) {
            if ($this->isAdmin($user)) {
                return true;
            }
            return false;
        });

        Gate::define('view-roles', function ($user) {
            return true;
        });

        Gate::define('manage-user-roles', function ($user) {
            if ($this->isAdmin($user)) {
                return true;
            }
            return false;
        });

        Gate::define('manage-roles', function ($user) {
            if ($this->isAdmin($user)) {
                return true;
            }
            return false;
        });

        Gate::define('view-user-security', function ($user, $profile) {
            if ($this->isAdmin($user)) {
                return true;
            }

            if ($user->id == $profile->id) {
                return true;
            }
            return false;
        });

        Gate::define('edit-user', function ($user, $profile_id) {
            if ($this->isAdmin($user)) {
                return true;
            }
            
            if ($user->id == $profile_id) {
                return true;
            }

            return false;
        });
        
        Gate::define('manage-users', function ($user) {
            if ($this->isAdmin($user)) {
                return true;
            }
            
            return false;
        });

        Gate::define('manage-games', function ($user) {
            if ($this->isAdmin($user)) {
                return true;
            }

            if ($this->isGameMaster($user)) {
                return true;
            }

            return false;
        });

        Gate::define('manage-guilds', function ($user) {
            if ($this->isAdmin($user)) {
                return true;
            }

            if ($this->isGameMaster($user)) {
                return true;
            }

            return false;
        });
    }

    private function isAdmin($user)
    {
        return Cache::remember('User:' . $user->id . ':is:Admin', $this->cache_time, function () use ($user) {
            $admin = Role::where('name', '=', 'Admin')->first();
            return $user->roles->contains($admin);
        });
    }

    private function isGameMaster($user)
    {
        return Cache::remember('User:' . $user->id . ':is:Game Master', $this->cache_time, function () use ($user) {
            $gm = Role::where('name', '=', 'Game Master')->first();
            return $user->roles->contains($gm);
        });
    }

    private function isCommunityManager($user)
    {
        return Cache::remember('User:' . $user->id . ':is:Community Manager', $this->cache_time, function () use ($user) {
            $cm = Role::where('name', '=', 'Community Manager')->first();
            return $user->roles->contains($cm);
        });
    }
}
