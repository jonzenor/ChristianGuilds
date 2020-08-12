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
            Alert::toast('Permission Denied', 'warning');
            return redirect('/');
        }

        $users = $this->getUsers();
        $userCount = $this->getUserCount();

        return view('acp.index')->with([
            'users' => $users,
            'userCount' => $userCount,
        ]);
    }
}
