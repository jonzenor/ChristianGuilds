<?php

namespace App\Http\Controllers;

use App\Realm;
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
}
