<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Genre;
use Gate;

class GenreController extends Controller
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
        $genres = $this->getPaginatedGenres($page);

        return view('genre.index')->with([
            'genres' => $genres,
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

        return view('genre.create');
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
            'short_name' => 'required|string|min:2|max:36',
        ]);

        $genre = new Genre();

        $genre->name = $request->name;
        $genre->short_name = $request->short_name;

        $genre->save();

        toast(__('game.genre_add_success'), 'success');
        $this->clearCache('genres');

        return redirect()->route('genre-list');
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

        $genre = $this->getGenre($id);

        return view('genre.edit')->with([
            'genre' => $genre,
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
            'short_name' => 'required|string|min:2|max:32',
        ]);

        $genre = $this->getGenre($id);

        $genre->name = $request->name;
        $genre->short_name = $request->short_name;

        $genre->save();

        toast(__('game.genre_update_success'), 'success');
        $this->clearCache('genres');
        $this->clearCache('genre', $genre->id);

        return redirect()->route('genre-list');
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
