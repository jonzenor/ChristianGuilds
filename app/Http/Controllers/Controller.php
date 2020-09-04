<?php

namespace App\Http\Controllers;

use App\Game;
use App\Role;
use App\User;
use App\Guild;
use App\Realm;
use App\Genre;
use App\Mail\newUser;
use App\UserSettings;
use App\ContactTopics;
use App\ContactSettings;
use App\Mail\AlertMessage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

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

    public function getUserContactSettings($id)
    {
        return Cache::remember('User:' . $id . ":ContactSettings", $this->cache_for, function () use ($id) {
            return ContactSettings::where('user_id', '=', $id)->get();
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

    public function getContactTopics()
    {
        return Cache::rememberForever('ContactTopic: all', function () {
            return ContactTopics::all();
        });
    }

    // Guild related cache functions

    public function getGuild($id)
    {
        return Cache::rememberForever('Guild:' . $id, function () use ($id) {
            return Guild::find($id);
        });
    }

    public function getGuilds()
    {
        return Cache::remember('Guilds:all', $this->cache_for, function () {
            return Guild::all();
        });
    }

    public function getPaginatedGuilds($page)
    {
        return Cache::remember('Guilds:page:' . $page, $this->cache_for, function () {
            return Guild::orderBy('name')->paginate(config('acp.paginate_games'));
        });
    }

    public function getGuildCount()
    {
        return Cache::rememberForever('Guilds:count', function () {
            return Guild::all()->count();
        });
    }

    public function getLatestGuilds()
    {
        return Cache::remember('Guilds:Latest', $this->cache_for, function () {
            return Guild::orderBy('created_at', 'desc')->limit(config('acp.items_limit'))->get();
        });
    }

    // Game related cache functions

    public function getGame($id)
    {
        return Cache::rememberForever('Game:' . $id, function () use ($id) {
            return Game::find($id);
        });
    }

    public function getGameCount()
    {
        return Cache::rememberForever('Games:count', function () {
            return Game::all()->count();
        });
    }

    public function getGames()
    {
        return Cache::rememberForever('Games', function () {
            return Game::orderBy('name')->where('status', '=', 'confirmed')->get();
        });
    }

    public function getPendingGames()
    {
        return Cache::rememberForever('Games:pending', function () {
            return Game::orderBy('name')->where('status', '=', 'pending')->get();
        });
    }

    public function getPendingGamesCount()
    {
        return Cache::rememberForever('Games:pending:count', function () {
            return Game::where('status', '=', 'pending')->get()->count();
        });
    }

    public function getPaginatedGames($page)
    {
        return Cache::remember('Games:page:' . $page, $this->cache_for, function () {
            return Game::orderBy('name')->paginate(config('acp.paginate_games'));
        });
    }

    public function getRealm($id)
    {
        return Cache::rememberForever('Realm:' . $id, function () use ($id) {
            return Realm::find($id);
        });
    }

    public function getGenres()
    {
        return Cache::rememberForever('Genres', function () {
            return Genre::orderBy('name')->get();
        });
    }

    public function getGenre($id)
    {
        return Cache::rememberForever('Genre:' . $id, function () use ($id) {
            return Genre::find($id);
        });
    }

    public function getGenreCount()
    {
        return Cache::rememberForever('Genres:count', function () {
            return Genre::all()->count();
        });
    }

    public function getPaginatedGenres($page)
    {
        return Cache::remember('Genres:page:' . $page, $this->cache_for, function () {
            return Genre::orderBy('name')->paginate(config('acp.paginate_games'));
        });
    }


    public function clearCache($what, $id = null)
    {
        if ($what == 'user') {
            Cache::forget('User:' . $id);
            Cache::forget('User:' . $id . ':ContactSettings');
        }

        if ($what == 'users' || $what == 'user') {
            Cache::forget('Users');
            Cache::forget('Users:Latest');

            $pages = ceil(($this->getUserCount()) / config('acp.paginate_games'));
            
            for ($i = 0; $i <= $pages; $i++) {
                Cache::forget('Users:page:' . $i);
            }
        }

        if ($what == 'guild') {
            Cache::forget('Guild:' . $id);
        }

        if ($what == 'guilds') {
            Cache::forget('Guilds:count');
        }

        if ($what == 'guild' || $what == 'guilds') {
            Cache::forget('Guilds');
            Cache::forget('Guilds:Latest');

            $pages = ceil(($this->getGuildCount()) / config('acp.paginate_games'));
            
            for ($i = 0; $i <= $pages; $i++) {
                Cache::forget('Guilds:page:' . $i);
            }
        }

        if ($what == "games") {
            Cache::forget('Games:count');

            $pages = ceil(($this->getGameCount()) / config('acp.paginate_games'));

            Cache::forget('Games');

            for ($i = 0; $i <= $pages; $i++) {
                Cache::forget('Games:page:' . $i);
            }
        }

        if ($what == "games-pending") {
            Cache::forget('Games:pending');
            Cache::forget('Games:pending:count');
        }

        if ($what == "game") {
            Cache::forget('Game:' . $id);
        }

        if ($what == "realm") {
            Cache::forget('Realm:' . $id);
        }

        if ($what == "genres") {
            $pages = ceil(($this->getGenreCount()) / config('acp.paginate_games'));

            Cache::forget('Genres');
            Cache::forget('Genres:count');
            
            for ($i = 0; $i <= $pages; $i++) {
                Cache::forget('Genres:page:' . $i);
            }
        }

        if ($what == "genre") {
            Cache::forget('Genre:' . $id);
        }
    }

    //*******************//
    // Search Functions //
    //*****************//
    public function searchGuilds($query)
    {
        return Cache::remember('Search:Guild:' . $query, config('site.cache_search_time'), function () use ($query) {
            $this->logEvent('Search Guild', 'Caching results for search ' . $query);
            return \App\Guild::search($query)->get();
        });
    }

    public function searchGames($query)
    {
        return Cache::remember('Search:Game:' . $query, config('site.cache_search_time'), function () use ($query) {
            $this->logEvent('Search Game', 'Caching results for search ' . $query);
            return \App\Game::search($query)->get();
        });
    }


    //*************************//
    // Send Message Functions //
    //***********************//
    public function sendAdminNotification($type, $data = null)
    {
        // Select admins
        $admins = $this->getAdminUsers();

        foreach ($admins as $admin) {
            if ($type == "new_user") {
                $contactSettings = ContactSettings::where('user_id', '=', $admin->id)->where('topic', '=', 'new_user')->get();

                foreach ($contactSettings as $setting) {
                    if ($setting->mode == "email") {
                        Log::channel('app')->info("[Email] Sending " . $type . " Message to " . $admin->name);
                        Mail::to($admin)->send(new NewUser($data));
                    }

                    if ($setting->mode == "pushover") {
                        $message = __('pushover.new_user_body', ['user' => $data->name]);
                        $title = __('pushover.new_user_title');

                        Log::channel('app')->info("[Pushover] Sending " . $type . " Message to " . $admin->name);

                        $this->sendPushover($admin, $message, $title);
                    }
                }
            }

            if ($type == "alert") {
                $contactSettings = ContactSettings::where('user_id', '=', $admin->id)->where('topic', '=', 'new_user')->get();

                foreach ($contactSettings as $setting) {
                    if ($setting->mode == "email") {
                        Log::channel('app')->info("[Email] Sending " . $type . " Message to " . $admin->name);
                        Mail::to($admin)->send(new AlertMessage($data));
                    }

                    if ($setting->mode == "pushover") {
                        $message = __('pushover.alert_body', ['user' => $data->name]);
                        $title = __('pushover.alert_title');

                        Log::channel('app')->info("[Pushover] Sending " . $type . " Message to " . $admin->name);

                        $this->sendPushover($admin, $message, $title);
                    }
                }
            }
        }
    }

    public function sendPushover($user, $message, $title = null)
    {
        $key = config('services.pushover.key');
        $user_settings = UserSettings::where('user_id', '=', $user->id)->first();

        if (!$title) {
            $title = "Christian Guilds";
        }

        curl_setopt_array($ch = curl_init(), array(
            CURLOPT_URL => "https://api.pushover.net/1/messages.json",
            CURLOPT_POSTFIELDS => array(
                "token" => $key,
                "user" => $user_settings->pushover_key,
                "message" => $message,
                "title" => $title,
            ),
            CURLOPT_SAFE_UPLOAD => true,
            CURLOPT_RETURNTRANSFER => true,
        ));

        curl_exec($ch);
        curl_close($ch);
    }


    //*****************************/
    // Google ReCaptcha analysis //
    //***************************/
    public function recaptchaCheck($recaptcha)
    {
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $remoteip = $_SERVER['REMOTE_ADDR'];
        $data = [
            'secret' => config('services.recaptcha.secret'),
            'response' => $recaptcha,
            'remoteip' => $remoteip
        ];

        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            ]
        ];

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $resultJson = json_decode($result);

        return $resultJson;
    }

    //***************************/
    // Other Helpful Functions //
    //*************************/

    public function getIp()
    {
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip); // just to be safe
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
    }
    
    public function logEvent($type, $message, $level = 'info')
    {
        if (auth()->user()) {
            $user = auth()->user()->name;
            $user_id = auth()->user()->id;
        } else {
            $user = "GUEST";
            $user_id = "0";
        }
        $text = "[" . $type . "] [USER: " . $user . " ID: " . $user_id . "] [URL: " . request()->path() . "] " . $message;

        if ($level == 'info') {
            Log::channel('app')->info($text);
        }

        if ($level == 'notice') {
            Log::channel('app')->notice($text);
        }

        if ($level == 'warning') {
            Log::channel('app')->warning($text);
        }
    }
}
