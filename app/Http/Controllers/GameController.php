<?php

namespace App\Http\Controllers;

use App\Game;
use Gate;
use Illuminate\Http\Request;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Gate::denies('manage-games')) {
            toast(__('site.permission_denied'), 'warning');
            return redirect()->route('home');
        }

        $page = (isset($_GET['page'])) ? $_GET['page'] : 1;
        $games = $this->getPaginatedGames($page);

        return view('game.index')->with([
            'games' => $games,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Gate::denies('manage-games')) {
            toast(__('site.permission_denied'), 'warning');
            return redirect()->route('home');
        }

        $genres = $this->getGenres();

        return view('game.create')->with([
            'genres' => $genres,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Gate::denies('manage-games')) {
            toast(__('site.permission_denied'), 'warning');
            return redirect()->route('home');
        }

        $this->validate($request, [
            'name' => 'required|string|min:3|max:255',
            'genre' => 'required|integer|max:10000',
        ]);

        $game = new Game();

        $game->name = $request->name;
        $game->genre_id = $request->genre;

        $game->save();

        toast(__('game.add_success'), 'success');
        $this->clearCache('games');

        return redirect()->route('game-list');
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
        if (Gate::denies('manage-games')) {
            toast(__('site.permission_denied'), 'warning');
            return redirect()->route('home');
        }

        $game = $this->getGame($id);

        $genres = $this->getGenres();

        return view('game.edit')->with([
            'game' => $game,
            'genres' => $genres,
        ]);
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
        if (Gate::denies('manage-games')) {
            toast(__('site.permission_denied'), 'warning');
            return redirect()->route('home');
        }

        $this->validate($request, [
            'name' => 'required|string|min:3|max:255',
            'genre' => 'required|integer|max:10000',
        ]);

        $game = $this->getGame($id);

        $game->name = $request->name;
        $game->genre_id = $request->genre;

        $game->save();

        toast(__('game.update_success'), 'success');
        $this->clearCache('games');
        $this->clearCache('game', $game->id);

        return redirect()->route('game-list');
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
