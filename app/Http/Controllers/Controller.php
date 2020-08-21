<?php

namespace App\Http\Controllers;

use App\ContactSettings;
use App\ContactTopics;
use App\Game;
use App\Genre;
use App\Mail\AlertMessage;
use App\Role;
use App\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Mail\newUser;
use App\UserSettings;
use Illuminate\Support\Facades\Log;

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

    public function getGenreCount()
    {
        return Cache::rememberForever('Genre:count', function () {
            return Genre::all()->count();
        });
    }

    public function getGames()
    {
        return Cache::rememberForever('Games', function () {
            return Game::all();
        });
    }

    public function getPaginatedGames($page)
    {
        return Cache::remember('Games:page:' . $page, $this->cache_for, function () {
            return Game::paginate(config('acp.paginate_games'));
        });
    }

    public function getGenres()
    {
        return Cache::rememberForever('Genres', function () {
            return Genre::all();
        });
    }

    public function getPaginatedGenres($page)
    {
        return Cache::remember('Genres:page:' . $page, $this->cache_for, function () {
            return Genre::paginate(config('acp.paginate_games'));
        });
    }


    public function clearCache($what, $id = null)
    {
        if ($what == 'user') {
            Cache::forget('User:' . $id);
            Cache::forget('User:' . $id . ':ContactSettings');
        }

        if ($what == "games") {
            $pages = ceil(($this->getGameCount()) / config('acp.paginate_games'));

            Cache::forget('Games');
            Cache::forget('Games:count');
            
            for ($i = 0; $i <= $pages; $i++) {
                Cache::forget('Games:page:' . $i);
            }
        }

        if ($what == "game") {
            Cache::forget('Game:' . $id);
        }
    }


    //**************************/
    // Send Message Functions //
    //************************/
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
}
