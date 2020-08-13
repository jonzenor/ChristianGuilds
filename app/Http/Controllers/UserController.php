<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Alert;

class UserController extends Controller
{
    public function show($id)
    {
        $user = $this->getUser($id);

        if (!$user) {
            toast(__('user.invalid_user'), 'error');
            return redirect()->route('home');
        }

        // Get roles that we can add the user to
        $newRoles = $this->getGlobalRoles();

        return view('user.show')->with([
            'user' => $user,
            'newRoles' => $newRoles,
        ]);
    }

    public function addRole(Request $request, $id)
    {
        $user = $this->getUser($id);

        if (!$user) {
            toast(__('user.invalid_user'), 'error');
            return redirect()->route('home');
        }

        $this->validate($request, [
            'role' => 'integer',
        ]);

        $role = $this->getRole($request->role);

        if (!$role) {
            toast(__('user.invalid_role'), 'error');
            return redirect()->route('profile', $user->id);
        }

        // Make sure the user isn't in this role already
        if ($user->roles->contains($role)) {
            toast(__('user.duplicate_role'), 'error');
            return redirect()->route('profile', $user->id);
        }

        $user->roles()->attach($request->role);

        return redirect()->route('profile', $user->id);
    }

    public function delRole($id, $role_id)
    {
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
        $user = $this->getUser($id);
        $role = $this->getRole($role_id);

        $user->roles()->detach($role->id);

        return redirect()->route('profile', $user->id);
    }
}
