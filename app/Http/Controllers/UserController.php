<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Alert;
use App\ContactSettings;
use App\ContactTopics;
use Gate;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\UserSettings;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        if (Gate::denies('view-acp')) {
            toast(__('site.permission_denied'), 'warning');
            Log::channel('app')->notice("[PERMISSION DENIED] User " . auth()->user()->name . " (ID: " . auth()->user()->id . ") attempted to access " . request()->path());
            return redirect()->route('home');
        }

        $page = (isset($_GET['page'])) ? $_GET['page'] : 1;
        $users = $this->getPaginatedUsers($page);

        return view('user.index')->with([
            'users' => $users,
        ]);
    }

    public function show($id)
    {
        $user = $this->getUser($id);

        if (!$user) {
            toast(__('user.invalid_user'), 'error');
            if (auth()->user()) {
                Log::channel('app')->warning("[Invalid User] User " . auth()->user()->name . " (ID: " . auth()->user()->id . ") attempted to access " . request()->path() . " but the user does not exist.");
            } else {
                Log::channel('app')->warning("[Invalid User] User GUEST (IP: " . request()->ip() . ") attempted to access " . request()->path() . " but the user does not exist.");
            }
            return redirect()->route('home');
        }

        // Get roles that we can add the user to
        $newRoles = $this->getGlobalRoles();

        return view('user.show')->with([
            'user' => $user,
            'newRoles' => $newRoles,
        ]);
    }

    public function edit($id)
    {
        if (Gate::denies('edit-user', $id)) {
            toast(__('site.permission_denied'), 'warning');
            Log::channel('app')->notice("[PERMISSION DENIED] User " . auth()->user()->name . " (ID: " . auth()->user()->id . ") attempted to access " . request()->path());
            return redirect()->route('home');
        }

        $user = $this->getUser($id);

        if (!$user) {
            toast(__('user.invalid_user'), 'error');
            Log::channel('app')->warning("[Invalid User] User " . auth()->user()->name . " (ID: " . auth()->user()->id . ") attempted to access " . request()->path() . " but the user does not exist.");
            return redirect()->route('home');
        }

        // Get a list of all of the things people can "subscribe" to receive notifications for
        $topics = $this->getContactTopics();
        $notifications = array();

        foreach ($topics as $topic) {
            $notifications[$topic->name] = array('email' => '', 'pushover' => '');
        }

        $userNotifications = $this->getUserContactSettings($user->id);

        foreach ($userNotifications as $notification) {
            $notifications[$notification->topic][$notification->mode] = true;
        }

        return view('user.edit')->with([
            'user' => $user,
            'topics' => $topics,
            'notifications' => $notifications,
        ]);
    }

    public function update(Request $request, $id)
    {
        if (Gate::denies('edit-user', $id)) {
            toast(__('site.permission_denied'), 'warning');
            Log::channel('app')->notice("[PERMISSION DENIED] User " . auth()->user()->name . " (ID: " . auth()->user()->id . ") attempted to access " . request()->path());
            return redirect()->route('home');
        }

        $user = $this->getUser($id);

        if (!$user) {
            toast(__('user.invalid_user'), 'error');
            Log::channel('app')->warning("[Invalid User] User " . auth()->user()->name . " (ID: " . auth()->user()->id . ") attempted to access " . request()->path() . " but the user does not exist.");
            return redirect()->route('home');
        }

        $this->validate($request, [
            'name' => 'required|string',
            'pushover_key' => 'nullable|string',
        ]);

        Log::channel('app')->info("[User Update] id: " . $user->id . " updated by " . auth()->user()->name . " (ID: " . auth()->user()->id . ") original: " . json_encode($user) . " Original User Settings: " . json_encode($user->settings) . " new: " . json_encode($request->all()));

        $topics = $this->getContactTopics();

        $topicArray = $request->toArray();
        foreach ($topics as $topic) {
            $contactModes = array('pushover', 'email');
            foreach ($contactModes as $mode) {
                // See if the user currently has this setting set
                $userSetting = ContactSettings::where('user_id', '=', $user->id)
                    ->where('topic', '=', $topic->name)
                    ->where('mode', '=', $mode)
                    ->first();

                // See what settings the user has selected
                if (isset($topicArray[$topic->name][$mode])) {
                    if (!$userSetting) {
                        // The user selected this option but doesn't have it set, so set it
                        $setting = new ContactSettings();

                        $setting->user_id = $user->id;
                        $setting->topic = $topic->name;
                        $setting->mode = $mode;
                        $setting->save();
                    }
                } else {
                    if ($userSetting) {
                        // If the user did not select this option, but has it saved, then remove it
                        ContactSettings::where('user_id', '=', $user->id)
                            ->where('topic', '=', $topic->name)
                            ->where('mode', '=', $mode)
                            ->delete();
                    }
                }
            }
        }

        $settings = $user->settings;

        if (!$settings) {
            $settings = new UserSettings();
            $settings->user_id = $user->id;
        }

        $settings->pushover_key = $request->pushover_key;

        $user->name = $request->name;

        $user->save();
        $settings->save();

        $this->clearCache('user', $user->id);

        Log::channel('app')->info("[User Update] id: " . $user->id . " Success");

        toast(__('user.profile_updated'), 'success');

        return redirect()->route('profile', $user->id);
    }

    public function addRole(Request $request, $id)
    {
        if (Gate::denies('manage-user-roles')) {
            toast(__('site.permission_denied'), 'warning');
            Log::channel('app')->notice("[PERMISSION DENIED] User " . auth()->user()->name . " (ID: " . auth()->user()->id . ") attempted to access " . request()->path());
            return redirect()->route('home');
        }

        $user = $this->getUser($id);

        if (!$user) {
            toast(__('user.invalid_user'), 'error');
            Log::channel('app')->warning("[Invalid User] User " . auth()->user()->name . " (ID: " . auth()->user()->id . ") attempted to access " . request()->path() . " but the user does not exist.");
            return redirect()->route('home');
        }

        $this->validate($request, [
            'role' => 'integer',
        ]);

        $role = $this->getRole($request->role);

        if (!$role) {
            toast(__('user.invalid_role'), 'error');
            Log::channel('app')->warning("[Invalid Role] User " . auth()->user()->name . " (ID: " . auth()->user()->id . ") attempted to access " . request()->path() . " with role " . $request->role . " but the role does not exist.");
            return redirect()->route('profile', $user->id);
        }

        // Make sure the user isn't in this role already
        if ($user->roles->contains($role)) {
            toast(__('user.duplicate_role'), 'error');
            return redirect()->route('profile', $user->id);
        }

        $user->roles()->attach($request->role);

        Cache::forget('User:' . $user->id . ':is:' . $role->name);

        toast(__('user.role_add_success'), 'success');

        return redirect()->route('profile', $user->id);
    }

    public function delRole($id, $role_id)
    {
        if (Gate::denies('manage-user-roles')) {
            toast(__('site.permission_denied'), 'warning');
            Log::channel('app')->notice("[PERMISSION DENIED] User " . auth()->user()->name . " (ID: " . auth()->user()->id . ") attempted to access " . request()->path());
            return redirect()->route('home');
        }

        $user = $this->getUser($id);
        $role = $this->getRole($role_id);

        $confirm['header'] = __('user.remove_role');
        $confirm['body'] = __('user.remove_role_text', ['user' => $user->name, 'role' => $role->name]);
        $confirm['action'] = route("remove-role-confirm", ['id' => $user->id, 'role' => $role->id]);
        $confirm['cancel'] = route('profile', $user->id);

        return view('site.confirm', [
            'user' => $user,
            'confirm_data' => $confirm,
        ]);
    }

    public function delRoleConfirm(Request $request, $id, $role_id)
    {
        if (Gate::denies('manage-user-roles')) {
            toast(__('site.permission_denied'), 'warning');
            Log::channel('app')->notice("[PERMISSION DENIED] User " . auth()->user()->name . " (ID: " . auth()->user()->id . ") attempted to access " . request()->path());
            return redirect()->route('home');
        }

        $user = $this->getUser($id);
        $role = $this->getRole($role_id);

        $user->roles()->detach($role->id);

        Cache::forget('User:' . $user->id . ':is:' . $role->name);

        toast(__('user.role_del_success'), 'success');
        
        Log::channel('app')->info("[Role Remove] User " . auth()->user()->name . " (ID: " . auth()->user()->id . ") removed role " . $role->name . " from User " . $user->name . " (ID: " . $user->id . ")");

        return redirect()->route('profile', $user->id);
    }
}
