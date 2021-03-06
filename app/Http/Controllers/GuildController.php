<?php

namespace App\Http\Controllers;

use App\Community;
use DB;
use Gate;
use App\Game;
use App\Page;
use App\Guild;
use App\GuildInvite;
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
            $this->logEvent('PERMISSION DENIED', 'Attempted to access the Guild ACP page without permissions', 'notice');
            return abort(404);
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
                'name' => 'string|required|min:' . config('site.input_name_min') . '|max:' . config('site.input_name_max'),
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
                'name' => 'string|required|min:' . config('site.input_name_min') . '|max:' . config('site.input_name_max'),
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

            $page = new Page();

            $page->guild_id = $guild->id;
            $page->title = __('guild.info_title');
            $page->content = __('guild.info_content');
            $page->slug = __('guild.info_slug');

            $page->save();

            $this->clearCache('guilds');
            $this->logEvent('Guild Created', 'Guild ' . $guild->name . ' created successfully with ID ' . $guild->id);

            DB::table('guild_members')->insert([
                'user_id' => auth()->user()->id,
                'guild_id' => $guild->id,
                'title' => 'Guild Master',
                'position' => 'owner',
            ]);

            $this->sendAdminNotification('new_guild', $guild);

            toast(__('guild.created_successfully'), 'success');

            return redirect()->route('guild-edit', $guild->id);
        }

        Alert::error(__('site.unknown_error'));
        Log::channel('app')->error("[Guild Create] GuildController@create somehow broke out of it's step process with no default set... User: " . auth()->user()->name . " (ID: " . auth()->user()->id . ") Request Data: " . json_encode($request->all()));
        return abort(404);
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

        if (!$guild) {
            $this->logEvent('Invalid Guild', 'Guild does not exist', 'warning');
            return abort(404);
        }

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
        if (Gate::denies('manage-guild', $id)) {
            $this->logEvent('PERMISSION DENIED', 'Attempted to access a guild edit page without permissions', 'notice');
            return abort(404);
        }

        $guild = $this->getGuild($id);

        if (!$guild) {
            $this->logEvent('Invalid Guild', 'Attempted to access a guild that does not exist.', 'warning');
            return abort(404);
        }

        return view('guild.edit')->with([
            'guild' => $guild,
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
        if (Gate::denies('manage-guild', $id)) {
            $this->logEvent('PERMISSION DENIED', 'Attempted to access ' . request()->path(), 'notice');
            return abort(404);
        }

        $guild = $this->getGuild($id);

        if (!$guild) {
            $this->logEvent('Invalid Guild', 'Attempted to access ' . request()->path() . ', but the guild does not exist.', 'warning');
            return abort(404);
        }

        $this->validate($request, [
            'name' => 'string|required|min:' . config('site.input_name_min') . '|max:' . config('site.input_name_max'),
            'server_name' => 'string|nullable|min:' . config('site.input_name_min') . '|max:' . config('site.input_name_max'),
            'description' => 'string|nullable|min:' . config('site.input_desc_min') . '|max:' . config('site.input_desc_max'),
        ]);

        $this->logEvent('Guild Update', 'Updating guild information from ' . json_encode($guild) . ' to ' . json_encode($request->all()));

        $guild->name = $request->name;
        $guild->description = $request->description;
        $guild->server_name = $request->server_name;

        if (isset($request->server_id)) {
            $this->validate($request, [
                'server' => 'integer|min:0|max:10000000|exists:servers,id',
            ]);

            if ($request->server_id == 0) {
                $guild->server_id = null;
            }

            if ($request->server_id > 0) {
                $guild->server_id = $request->server_id;
                $guild->server_name = null;
            }
        }

        $guild->save();

        $this->clearCache('guild', $guild->id);

        Alert::success(__('guild.update_successful'));

        return redirect()->route('guild', $guild->id);
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

    public function joinCommunity(Request $request, $id)
    {
        if (Gate::denies('manage-guild', $id)) {
            $this->logEvent('PERMISSION DENIED', 'Attempted to access ' . request()->path(), 'notice');
            return abort(404);
        }

        $guild = $this->getGuild($id);

        if (!$guild) {
            $this->logEvent('Invalid Guild', 'Attempted to access ' . request()->path() . ', but the guild does not exist.', 'warning');
            return abort(404);
        }

        $this->validate($request, [
            'invite_code' => 'string|required|min:32|max:32',
        ]);

        $invite = GuildInvite::where('code', '=', $request->invite_code)->first();

        if (!$invite) {
            $this->logEvent('Invalid Community Invite', 'Attempted to use invite code ' . $request->invite_code . ' for Guild ID ' . $guild->id, 'notice');
            Alert::error(__('community.invalid_code'));
            return redirect()->route('guild', $guild->id);
        }

        $community = Community::find($invite->community_id);

        return view('community.join', [
            'guild' => $guild,
            'community' => $community,
            'invite' => $invite,
        ]);
    }

    public function joinCommunityConfirm(Request $request, $id)
    {
        if (Gate::denies('manage-guild', $id)) {
            $this->logEvent('PERMISSION DENIED', 'Attempted to access ' . request()->path(), 'notice');
            return abort(404);
        }

        $guild = $this->getGuild($id);

        if (!$guild) {
            $this->logEvent('Invalid Guild', 'Attempted to access ' . request()->path() . ', but the guild does not exist.', 'warning');
            return abort(404);
        }

        $this->validate($request, [
            'code' => 'string|required|min:32|max:32',
        ]);

        $invite = GuildInvite::where('code', '=', $request->code)->first();

        if (!$invite) {
            $this->logEvent('Invalid Community Invite', 'Attempted to use invite code ' . $request->code . ' for Guild ID ' . $guild->id, 'notice');
            Alert::error(__('community.invalid_code'));
            return redirect()->route('guild', $guild->id);
        }

        $community = Community::find($invite->community_id);

        $guild->community_id = $community->id;
        $guild->save();

        $invite->guild_id = $guild->id;
        $invite->save();

        $this->logEvent('Community Joined', 'Guild ' . json_encode($guild) . ' has joined Community ' . json_encode($community) . ' using invite code ' . $invite->code, 'info');
        Alert::success(__('community.joined'));

        $this->clearCache('community', $community->id);
        $this->clearCache('guild', $guild->id);

        return redirect()->route('guild', $guild->id);
    }

    public function leaveCommunity($id)
    {
        if (Gate::denies('own-guild', $id)) {
            $this->logEvent('PERMISSION DENIED', 'Attempted to access leave Community page without permission.', 'notice');
            return abort(404);
        }

        $guild = $this->getGuild($id);

        if (!$guild) {
            $this->logEvent('Invalid Guild', 'Attempted to access a guild that does not exist.', 'warning');
            return abort(404);
        }

        $invite = GuildInvite::where('guild_id', '=', $guild->id)->first();

        if (!$invite) {
            $this->logEvent('Invalid Invite', 'Attempted to leave community when not joined to one. ' . json_encode($guild), 'warning');
            return abort(404);
        }

        return view('community.leave', [
            'guild' => $guild,
            'invite' => $invite,
        ]);
    }

    public function leaveCommunityConfirm(Request $request, $id)
    {
        if (Gate::denies('own-guild', $id)) {
            $this->logEvent('PERMISSION DENIED', 'Attempted to force guild to leave its community.', 'notice');
            return abort(404);
        }

        $guild = $this->getGuild($id);

        if (!$guild) {
            $this->logEvent('Invalid Guild', 'Attempted to force guild to leave its community, but the guild does not exist.', 'warning');
            return abort(404);
        }

        $invite = GuildInvite::where('guild_id', '=', $guild->id)->first();

        if (!$invite) {
            $this->logEvent('Invalid Community', 'Attempted to leave a community but is not a member of one.', 'notice');
            Alert::error(__('community.invalid_invite'));
            return redirect()->route('guild', $guild->id);
        }

        $community = Community::find($invite->community_id);

        $guild->community_id = null;
        $guild->save();

        $invite->guild_id = null;
        $invite->save();

        $this->logEvent('Community Abandoned', 'Guild ' . json_encode($guild) . ' has left Community ' . json_encode($community), 'info');
        Alert::success(__('community.abandoned'));

        $this->clearCache('community', $community->id);
        $this->clearCache('guild', $guild->id);

        return redirect()->route('guild', $guild->id);
    }
}
