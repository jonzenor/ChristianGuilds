<?php

namespace App\Http\Controllers;

//use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function acp()
    {
        if (Gate::denies('view-acp')) {
            toast('Permission Denied', 'warning');
            Log::channel('app')->notice("[PERMISSION DENIED] User " . auth()->user()->name . " (ID: " . auth()->user()->id . ") attempted to access " . request()->path());
            return redirect()->route('home');
        }

        $users = $this->getLatestUsers();
        $count['users'] = $this->getUserCount();

        $roles = $this->getGlobalRoles();

        $count['games'] = $this->getGameCount();
        $count['genres'] = $this->getGenreCount();

        $guilds = $this->getLatestGuilds();
        $count['guilds'] = $this->getGuildCount();

        $count['games_pending'] = $this->getPendingGamesCount();

        return view('acp.index')->with([
            'users' => $users,
            'roles' => $roles,
            'guilds' => $guilds,
            'count' => $count,
        ]);
    }

    public function sendTestEmail()
    {
        $user = $this->getUser(4);
        $this->mailAdminNewUser($user);

        return redirect('/test_email');
    }

    public function forge()
    {

        $user = $this->getUser(Auth::id());
        if (env("APP_ENV") != "forge" && env("APP_ENV") != "local") {
            Log::channel('app')->alert("Attempt to access Forge Test Page by " . json_encode($user));
            $this->sendAdminNotification("alert", $user);
            return redirect()->route('home');
        }
        
        $global = $this->getGlobalRoles();

        return view('acp.forge')->with([
            'roles' => $global,
        ]);
    }

    public function forgePowerUp($id)
    {
        $user = $this->getUser(Auth::id());

        $role = $this->getRole($id);

        Log::channel('app')->alert("Granting " . $role->name . " Powers to " . json_encode($user));
        $this->sendAdminNotification("alert", $user);
        
        $user->roles()->attach($role->id);
        Cache::flush();
        
        toast("Granted " . $role->name . " powers", "success");

        return redirect()->route('profile', $user->id);
    }
}
