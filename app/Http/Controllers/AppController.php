<?php

namespace App\Http\Controllers;

use App\App;
use App\Question;
use Gate;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

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
        Alert::success(__('app.created'));
        
        return redirect()->route('app-manage', $app->id);
    }

    public function edit($id)
    {
        $app = $this->getApp($id);

        if (!$app) {
            $this->logEvent('Invalid Application', 'Attempted to access app management for an application that does not exist.', 'warning');
            return abort(404);
        }

        $guild = $this->getGuild($app->org_id);

        if (Gate::denies('manage-guild', $guild->id)) {
            $this->logEvent('PERMISSION DENIED', 'Attempted to access guild app create page.', 'notice');
            return abort(404);
        }

        return view('guild.app.edit', [
            'app' => $app,
            'guild' => $guild,
        ]);
    }

    public function update(Request $request, $id)
    {
        $app = $this->getApp($id);

        if (!$app) {
            $this->logEvent('Invalid Application', 'Attempted to access app management for an application that does not exist.', 'warning');
            return abort(404);
        }

        $guild = $this->getGuild($app->org_id);

        if (Gate::denies('manage-guild', $guild->id)) {
            $this->logEvent('PERMISSION DENIED', 'Attempted to access guild app create page.', 'notice');
            return abort(404);
        }

        $this->validate($request, [
            'name' => 'string|required|min:' . config('site.input_name_min') . '|max:' . config('site.input_name_max'),
            'visibility' => 'string|required|in:private,public',
            'promote_to' => 'integer|required|min:1',
        ]);

        $app->title = $request->name;
        $app->visibility = $request->visibility;
        $app->promotion_rank = $request->promote_to;
        $app->save();

        $this->clearCache('app', $app->id);
        $this->logEvent('Guild App Updated', 'Application for guild ' . $guild->name . ' (ID: ' . $guild->id . ') updated. Data:' . json_encode($app));
        Alert::success(__('app.updated'));

        return redirect()->route('app-manage', $app->id);
    }

    public function manage($id)
    {
        $app = $this->getApp($id);

        if (!$app) {
            $this->logEvent('Invalid Application', 'Attempted to access app management for an application that does not exist.', 'warning');
            return abort(404);
        }

        $guild = $this->getGuild($app->org_id);

        if (Gate::denies('manage-guild', $guild->id)) {
            $this->logEvent('PERMISSION DENIED', 'Attempted to access guild app create page.', 'notice');
            return abort(404);
        }

        return view('guild.app.manage', [
            'app' => $app,
            'guild' => $guild,
        ]);
    }

    public function addQuestion(Request $request, $id)
    {
        $app = $this->getApp($id);

        if (!$app) {
            $this->logEvent('Invalid Application', 'Attempted to access app management for an application that does not exist.', 'warning');
            return abort(404);
        }

        $guild = $this->getGuild($app->org_id);

        if (Gate::denies('manage-guild', $guild->id)) {
            $this->logEvent('PERMISSION DENIED', 'Attempted to access guild app create page.', 'notice');
            return abort(404);
        }

        $this->validate($request, [
            'text' => 'string|required|min:' . config('site.input_name_min') . '|max:' . config('site.input_name_max'),
        ]);

        $question = new Question();
        $question->app_id = $app->id;
        $question->type = "text";
        $question->number = $app->questions->count() + 1;
        $question->text = $request->text;
        $question->save();

        $this->logEvent('App Question Added', 'A new question was added to the app ' . json_encode($app) . ' for guild ' . $guild->name . ' (ID: ' . $guild->id . ') Data:' . json_encode($request->all()));
        toast(__('app.question_added'), 'success');
        $this->clearCache('app', $app->id);

        return redirect()->route('app-manage', $app->id);
    }
}
