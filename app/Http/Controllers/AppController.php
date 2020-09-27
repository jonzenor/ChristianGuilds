<?php

namespace App\Http\Controllers;

use App\App;
use Gate;
use Illuminate\Http\Request;

class AppController extends Controller
{
    public function guildIndex($id)
    {
        if (Gate::denies('manage-guild', $id)) {
            $this->logEvent('PERMISSION DENIED', 'Attempted to access guild apps list page.', 'notice');
            return abort(404);
        }

        $guild = $this->getGuild($id);

        if (!$guild) {
            $this->logEvent('Invalid Guild', 'Attempted to access guild list for a guild that does not exist.', 'warning');
            return abort(404);
        }

        return view('guild.apps', [
            'guild' => $guild,
        ]);
    }

    public function create($id)
    {
        if (Gate::denies('manage-guild', $id)) {
            $this->logEvent('PERMISSION DENIED', 'Attempted to access guild app create page.', 'notice');
            return abort(404);
        }

        $guild = $this->getGuild($id);

        if (!$guild) {
            $this->logEvent('Invalid Guild', 'Attempted to access guild list for a guild that does not exist.', 'warning');
            return abort(404);
        }

        return view('guild.app.create', [
            'guild' => $guild,
        ]);
    }

    public function store(Request $request, $id)
    {
        if (Gate::denies('manage-guild', $id)) {
            $this->logEvent('PERMISSION DENIED', 'Attempted to access guild app create page.', 'notice');
            return abort(404);
        }

        $guild = $this->getGuild($id);

        if (!$guild) {
            $this->logEvent('Invalid Guild', 'Attempted to access guild list for a guild that does not exist.', 'warning');
            return abort(404);
        }

        $this->validate($request, [
            'name' => 'string|required|min:' . config('site.input_name_min') . '|max:' . config('site.input_name_max'),
            'visibility' => 'string|required|in:private,public',
            'promote_to' => 'integer|required|min:1',
        ]);

        if ($guild->role_type == "simple") {
            $this->validate($request, [
                'promote_to' => 'integer|required|min:1|max:3',
            ]);
        }

        $app = new App();

        $app->org_id = $guild->id;
        $app->org_type = "guild";
        $app->title = $request->name;
        $app->visibility = $request->visibility;
        $app->promotion_rank = $request->promote_to;

        $app->save();

        $this->logEvent('Guild App Creation', 'Application created for guild ' . $guild->name . ' (ID: ' . $guild->id . ') Data:' . json_encode($app));
        
        return redirect()->route('app-manage', $app->id);
    }
}
