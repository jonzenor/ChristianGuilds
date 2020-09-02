<?php

namespace App\Http\Controllers;

use Gate;
use Alert;
use App\Game;
use App\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
            Log::channel('app')->notice("[PERMISSION DENIED] User " . auth()->user()->name . " (ID: " . auth()->user()->id . ") attempted to access " . request()->path());
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
            Log::channel('app')->notice("[PERMISSION DENIED] User " . auth()->user()->name . " (ID: " . auth()->user()->id . ") attempted to access " . request()->path());
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
            Log::channel('app')->notice("[PERMISSION DENIED] User " . auth()->user()->name . " (ID: " . auth()->user()->id . ") attempted to access " . request()->path());
            return redirect()->route('home');
        }

        $this->validate($request, [
            'name' => 'required|string|min:3|max:255',
            'genre' => 'required|integer|max:10000',
        ]);

        Log::channel('app')->info("[Game Create] User " . auth()->user()->name . " (ID: " . auth()->user()->id . ") Attempting to CREATE the Game " . json_encode($request->all()));

        $game = new Game();

        $game->name = $request->name;
        $game->genre_id = $request->genre;

        $game->save();

        Log::channel('app')->info("[Game Create] Game Created Successfully with ID " . $game->id);

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
        $game = $this->getGame($id);

        if (!$game) {
            toast(__('game.invalid_game'), 'error');
            $this->logEvent('Invalid Game', "Attempted to access a game that does not exist.", 'warning');
            return redirect()->route('game-list-pending');
        }

        return view('game.show')->with([
            'game' => $game,
        ]);
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
            Log::channel('app')->notice("[PERMISSION DENIED] User " . auth()->user()->name . " (ID: " . auth()->user()->id . ") attempted to access " . request()->path());
            return redirect()->route('home');
        }

        $game = $this->getGame($id);

        if (!$game) {
            toast(__('game.invalid_game'), 'error');
            $this->logEvent('Invalid Game', "Attempted to access a game that does not exist.", 'warning');
            return redirect()->route('game-list-pending');
        }

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
            Log::channel('app')->notice("[PERMISSION DENIED] User " . auth()->user()->name . " (ID: " . auth()->user()->id . ") attempted to access " . request()->path());
            return redirect()->route('home');
        }

        $this->validate($request, [
            'name' => 'required|string|min:3|max:255',
            'genre' => 'required|integer|max:10000',
        ]);

        $game = $this->getGame($id);

        if (!$game) {
            toast(__('game.invalid_game'), 'error');
            $this->logEvent('Invalid Game', "Attempted to access a game that does not exist.", 'warning');
            return redirect()->route('game-list-pending');
        }

        Log::channel('app')->info("[Game Update] User " . auth()->user()->name . " (ID: " . auth()->user()->id . ") UPDATING the Game " . json_encode($game) . " NEW DATA: " . json_encode($request->all()));

        $game->name = $request->name;
        $game->genre_id = $request->genre;

        $game->save();

        Log::channel('app')->info("[Game Update] Game updated successfully.");

        toast(__('game.update_success'), 'success');
        $this->clearCache('games');
        $this->clearCache('game', $game->id);

        return redirect()->route('game-list');
    }

    public function pending()
    {
        if (Gate::denies('manage-games')) {
            toast(__('site.permission_denied'), 'warning');
            Log::channel('app')->notice("[PERMISSION DENIED] User " . auth()->user()->name . " (ID: " . auth()->user()->id . ") attempted to access " . request()->path());
            return redirect()->route('home');
        }

        $games = $this->getPendingGames();

        if (!$games->count()) {
            Alert::info(__('game.no_pending_games'));
            return redirect()->route('game-list');
        }

        $genres = $this->getGenres();

        $listGames = $this->getGames();

        return view('game.pending')->with([
            'games' => $games,
            'listGames' => $listGames,
            'genres' => $genres,
        ]);
    }

    public function approvePending(Request $request, $id)
    {
        if (Gate::denies('manage-games')) {
            toast(__('site.permission_denied'), 'warning');
            Log::channel('app')->notice("[PERMISSION DENIED] User " . auth()->user()->name . " (ID: " . auth()->user()->id . ") attempted to access " . request()->path());
            return redirect()->route('home');
        }

        $this->validate($request, [
            'name' => 'string|required|max:255',
            'genre' => 'integer|required|min:1|max:1000',
        ]);

        $game = $this->getGame($id);

        if (!$game) {
            toast(__('game.invalid_game'), 'error');
            $this->logEvent('Invalid Game', 'attempted to access ' . request()->path() . " but the game does not exist.", 'warning');
            return redirect()->route('game-list-pending');
        }

        $game->name = $request->name;
        $game->genre_id = $request->genre;
        $game->status = 'confirmed';

        $game->save();

        $this->logEvent('Game Approved', 'User suggested game was approved for use on the site. ' . json_encode($game));

        $this->clearCache('game', $game->id);
        $this->clearCache('games-pending');

        Alert::success(__('game.approved_success'));

        return redirect()->route('game-list-pending');
    }

    public function rejectPending(Request $request, $id)
    {
        if (Gate::denies('manage-games')) {
            toast(__('site.permission_denied'), 'warning');
            Log::channel('app')->notice("[PERMISSION DENIED] User " . auth()->user()->name . " (ID: " . auth()->user()->id . ") attempted to access " . request()->path());
            return redirect()->route('home');
        }

        $this->validate($request, [
            'game' => 'integer|required|min:1|max:1000000',
        ]);

        $game = $this->getGame($id);

        if (!$game) {
            toast(__('game.invalid_game'), 'error');
            $this->logEvent('Invalid Game', 'attempted to access ' . request()->path() . " but the game does not exist.", 'warning');
            return redirect()->route('game-list-pending');
        }

        $guild = $this->getGuild($game->guild->id);
        if (!$guild) {
            toast(__('game.invalid_guild'), 'error');
            $this->logEvent('Invalid Guild', 'attempted to access ' . request()->path() . " but the guild attached to this game does not exist. " . json_endcode($game), 'warning');
            return redirect()->route('game-list-pending');
        }

        $guild->game_id = $request->game;
        $guild->save();

        $game->status = 'rejected';
        $game->save();

        $this->clearCache('game', $game->id);
        $this->clearCache('games-pending');
        $this->clearCache('guild', $guild->id);

        Alert::success(__('guild.reassigned_success'));

        return redirect()->route('game-list-pending');
    }

    public function setPendingGenre(Request $request, $id)
    {
        if (Gate::denies('manage-games')) {
            toast(__('site.permission_denied'), 'warning');
            Log::channel('app')->notice("[PERMISSION DENIED] User " . auth()->user()->name . " (ID: " . auth()->user()->id . ") attempted to access " . request()->path());
            return redirect()->route('home');
        }

        $game = $this->getGame($id);

        if (!$game) {
            toast(__('game.invalid_game'), 'error');
            $this->logEvent('Invalid Game', 'attempted to access ' . request()->path() . " but the game does not exist.", 'warning');
            return redirect()->route('home');
        }

        $this->validate($request, [
            'genre' => 'integer|required|min:0|max:1000',
        ]);

        if ($request->genre > 0) {
            $game->genre_id = $request->genre;
            $game->save();
        }

        if ($request->genre == 0) {
            $this->validate($request, [
                'name' => 'string|required|max:255',
                'short_name' => 'string|required|min:2|max:12',
            ]);
            
            $genre = new Genre();

            $genre->name = $request->name;
            $genre->short_name = $request->short_name;
            $genre->save();

            $this->logEvent('Genre Added', 'Genre added by Game Master. ' . json_encode($genre));

            $this->clearCache('genres');

            $game->genre_id = $genre->id;
            $game->save();
        }

        $this->clearCache('game', $game->id);
        $this->clearCache('games-pending');

        toast(__('game.genre_updated'), 'success');

        return redirect()->route('game-list-pending');
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
