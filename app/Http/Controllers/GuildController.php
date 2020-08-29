<?php

namespace App\Http\Controllers;

use Gate;
use App\Guild;
use App\Game;
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
        if (Gate::denies('manage-guilds')) {
            toast(__('site.permission_denied'), 'warning');
            Log::channel('app')->notice("[PERMISSION DENIED] [User  " . auth()->user()->id . " " . auth()->user()->name . "] Attempted to access " . request()->path());
            return redirect()->route('home');
        }

        $page = (isset($_GET['page'])) ? $_GET['page'] : 1;
        $guilds = $this->getPaginatedGuilds($page);

        return view('guild.index')->with([
            'guilds' => $guilds,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // If STEP does not exist, then we're on step 1
        // Step 1 == Ask the user for what game their guild is for
        if (!isset($request->step)) {
            $games = $this->getGames();

            return view('guild.create')->with([
                'games' => $games,
                'step' => 1,
            ]);
        }

        $this->validate($request, [
            'step' => 'integer|required',
        ]);

        // STEP 2 == Ask for new game name, if game doesn't exist
        if ($request->step == "2") {
            $this->validate($request, [
                'game' => 'integer|required',
            ]);

            // Game does not exist. Go to step 2
            if ($request->game == 0) {
                $genres = $this->getGenres();
                
                return view('guild.create')->with([
                    'step' => 2,
                    'genres' => $genres,
                ]);
            }

            // The game does exist in the database, so go to STEP 4
            if ($request->game > 0) {
                $game = $this->getGame($request->game);

                return view('guild.create')->with([
                    'step' => 4,
                    'game' => $game,
                ]);
            }
        }

        // STEP 3 == Save new game for user in a temp var
        if ($request->step == "3") {
            $this->validate($request, [
                'name' => 'string|required',
                'genre' => 'integer|required',
            ]);

            $game = new Game();

            $game->id = 0;
            $game->name = $request->name;
            $game->genre_id = $request->genre;

            return view('guild.create')->with([
                'step' => 4,
                'game' => $game,
            ]);
        }

        // STEP 4 == Get the guild name and create guild
        if ($request->step == "4") {
            $this->validate($request, [
                'game' => 'integer|required',
                'name' => 'string|required|max:256',
                'game_name' => 'string|required',
                'genre_id' => 'integer|required',
            ]);

            if ($request->game == 0) {
                $game = new Game();

                $game->name = $request->game_name;
                $game->genre_id = $request->genre_id;
                $game->status = "pending";

                $game->save();

                $request->game = $game->id;

                $this->logEvent('Guild Added Game', 'Added the game ' . json_encode($game) . ' for guild ' . $request->name);

                $this->clearCache('games');
                $this->clearCache('games-pending');
            }

            $guild = new Guild();

            $this->logEvent('Guild Create', 'Created guild ' . json_encode($request->all()));

            $guild->name = $request->name;
            $guild->game_id = $request->game;
            $guild->owner_id = auth()->user()->id;

            $guild->save();

            $this->clearCache('guilds');

            $this->logEvent('Guild Created', 'Guild created successfully with ID ' . $guild->id);

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
        $guild = $this->getGuild($id);

        return view('guild.show')->with([
            'guild' => $guild,
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
