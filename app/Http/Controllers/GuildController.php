<?php

namespace App\Http\Controllers;

use App\Guild;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use RealRashid\SweetAlert\Facades\Alert;

class GuildController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (!isset($request->step)) {
            $games = $this->getGames();

            return view('guild.create')->with([
                'games' => $games,
                'step' => 1,
            ]);
        }

        if ($request->step == "2") {
            $this->validate($request, [
                'game' => 'integer|required',
            ]);

            $game = $this->getGame($request->game);

            return view('guild.create')->with([
                'step' => 2,
                'game' => $game,
            ]);
        }

        if ($request->step == "3") {
            $this->validate($request, [
                'game' => 'integer|required',
                'name' => 'string|required|max:256',
            ]);

            $guild = new Guild();

            Log::channel('app')->info("[Guild Create] User " . auth()->user()->name . " (ID: " . auth()->user()->id . ") is Attempting to Create a Guild " . json_encode($request->all()));

            $guild->name = $request->name;
            $guild->game_id = $request->game;
            $guild->owner_id = auth()->user()->id;

            $guild->save();

            Log::channel('app')->info("[Guild Create] Guild created successfully with ID " . $guild->id);
            toast(__('guild.created_successfully'), 'success');

            return redirect()->route('guild', $guild->id);
        }

        Alert::error(__('site.unknown_error'));
        Log::channel('app')->error("[Guild Create] GuildController@create somehow broke out of it's step process with no default set... User: " . auth()->user()->name . " (ID: " . auth()->user()->id . ") Request Data: " . json_encode($request->all()));
        return redirect()->route('home');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
