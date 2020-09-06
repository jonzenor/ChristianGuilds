<?php

namespace App\Http\Controllers;

use App\Realm;
use App\Server;
use Gate;
use Illuminate\Http\Request;

class ServerController extends Controller
{
    public function edit($id)
    {
        if (Gate::denies('manage-games')) {
            $this->logEvent(__('site.permission_denied_label'), __('site.permission_denied_message'), 'notice');
            return abort(404);
        }

        $game = $this->getGame($id);

        if (!$game) {
            $this->logEvent('Invalid Game', 'Attempting to access a game that does not exist.', 'warning');
            return abort(404);
        }

        return view('game.servers')->with([
            'game' => $game,
        ]);
    }

    public function storeRealm(Request $request, $id)
    {
        if (Gate::denies('manage-games')) {
            $this->logEvent(__('site.permission_denied_label'), __('site.permission_denied_message'), 'notice');
            return abort(404);
        }

        $game = $this->getGame($id);

        if (!$game) {
            $this->logEvent('Invalid Game', 'Attempting to access a game that does not exist.', 'warning');
            return abort(404);
        }

        $this->validate($request, [
            'name' => 'string|required|min:' . config('site.input_name_min') . '|max:' . config('site.input_name_max'),
            'type' => 'string|required|min:' . config('site.input_short_genre_min') . '|max:' . config('site.input_short_genre_max'),
        ]);

        $realm = new Realm();

        $realm->name = $request->name;
        $realm->type = $request->type;
        $realm->game_id = $game->id;

        $realm->save();

        $this->clearCache('game', $game->id);

        $this->logEvent('[REALM ADDED]', 'New realm added to ' . $game->name);
        toast(__('game.realm_added'), 'success');
        
        return redirect()->route('game-manage-servers', $game->id);
    }

    public function storeServer(Request $request, $id)
    {
        if (Gate::denies('manage-games')) {
            $this->logEvent(__('site.permission_denied_label'), __('site.permission_denied_message'), 'notice');
            return abort(404);
        }

        $realm = $this->getRealm($id);

        if (!$realm) {
            $this->logEvent('Invalid Realm', 'Attempting to access a realm that does not exist.', 'warning');
            return abort(404);
        }

        $this->validate($request, [
            'name' => 'string|required|min:' . config('site.input_name_min') . '|max:' . config('site.input_name_max'),
        ]);

        $server = new Server();

        $server->name = $request->name;
        $server->realm_id = $realm->id;

        $server->save();

        $this->clearCache('game', $realm->game->id);
        $this->clearCache('realm', $realm->id);

        $this->logEvent('[SERVER ADDED]', 'New server added to ' . $realm->name . ' (Game: ' . $realm->game->name . ')');
        toast(__('game.server_added'), 'success');
        
        return redirect()->route('game-manage-servers', $realm->game->id);
    }

    public function editRealm($id)
    {

        if (Gate::denies('manage-games')) {
            $this->logEvent(__('site.permission_denied_label'), __('site.permission_denied_message'), 'notice');
            return abort(404);
        }

        $realm = $this->getRealm($id);
        
        if (!$realm) {
            $this->logEvent('Invalid Realm', 'Attempting to access a realm that does not exist.', 'warning');
            return abort(404);
        }

        return view('game.realm.edit')->with([
            'realm' => $realm,
        ]);
    }

    public function updateRealm(Request $request, $id)
    {
        if (Gate::denies('manage-games')) {
            $this->logEvent(__('site.permission_denied_label'), __('site.permission_denied_message'), 'notice');
            return abort(404);
        }

        $realm = $this->getRealm($id);
        
        if (!$realm) {
            $this->logEvent('Invalid Realm', 'Attempting to access a realm that does not exist.', 'warning');
            return abort(404);
        }

        $realm->name = $request->name;
        $realm->type = $request->type;

        $realm->save();

        $this->clearCache('realm', $realm->id);

        toast(__('game.realm_updated'), 'success');

        return redirect()->route('game-manage-servers', $realm->game_id);
    }

    public function editServer($id)
    {
        if (Gate::denies('manage-games')) {
            $this->logEvent(__('site.permission_denied_label'), __('site.permission_denied_message'), 'notice');
            return abort(404);
        }

        $server = $this->getServer($id);
        
        if (!$server) {
            $this->logEvent('Invalid Server', 'Attempting to access a server that does not exist.', 'warning');
            return abort(404);
        }

        return view('game.server.edit')->with([
            'server' => $server,
        ]);
    }

    public function updateServer(Request $request, $id)
    {
        if (Gate::denies('manage-games')) {
            $this->logEvent(__('site.permission_denied_label'), __('site.permission_denied_message'), 'notice');
            return abort(404);
        }

        $server = $this->getServer($id);
        
        if (!$server) {
            $this->logEvent('Invalid Server', 'Attempting to access a server that does not exist.', 'warning');
            return abort(404);
        }

        $server->name = $request->name;

        $server->save();

        $this->clearCache('server', $server->id);

        toast(__('game.server_updated'), 'success');

        return redirect()->route('game-manage-servers', $server->realm->game_id);
    }
}
