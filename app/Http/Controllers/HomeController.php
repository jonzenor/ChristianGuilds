<?php

namespace App\Http\Controllers;

//use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Gate;

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
            return redirect()->route('home');
        }

        $users = $this->getLatestUsers();
        $userCount = $this->getUserCount();

        $roles = $this->getGlobalRoles();

        return view('acp.index')->with([
            'users' => $users,
            'userCount' => $userCount,
            'roles' => $roles,
        ]);
    }

    public function sendTestEmail()
    {
        $user = $this->getUser(4);
        $this->mailAdminNewUser($user);

        return redirect('/test_email');
    }
}
