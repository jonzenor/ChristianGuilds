<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show($id)
    {
        $user = $this->getUser($id);

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

        $this->validate($request, [
            'role' => 'integer',
        ]);

        $user->role()->attach($request->role);

        return redirect()->route('profile', $user->id);
    }
}
