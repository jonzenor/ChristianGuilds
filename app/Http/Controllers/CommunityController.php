<?php

namespace App\Http\Controllers;

use Gate;
use App\Community;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RealRashid\SweetAlert\Facades\Alert;

class CommunityController extends Controller
{
    public function index()
    {
        if (Gate::denies('manage-guilds')) {
            $this->logEvent('PERMISSION DENIED', 'Attempted to access the Community ACP page without permissions', 'notice');
            return abort(404);
        }

        $page = (isset($_GET['page'])) ? $_GET['page'] : 1;
        $communities = $this->getPaginatedCommunities($page);

        return view('community.index')->with([
            'communities' => $communities,
        ]);
    }

    public function create()
    {
        if (Gate::denies('create-community')) {
            $this->logEvent('PERMISSION DENIED', 'Attempted to create a community without permissions', 'notice');
            return abort(404);
        }

        return view('community.create');
    }

    public function store(Request $request)
    {
        if (Gate::denies('create-community')) {
            $this->logEvent('PERMISSION DENIED', 'Attempted to create a community without permissions', 'notice');
            return abort(404);
        }

        $this->validate($request, [
            'name'  => 'string|required|min:' . config('site.input_name_min') . '|max:' . config('site.input_name_max'),
            'description' => 'string|required|min:' . config('site.input_desc_min') . '|max:' . config('site.input_desc_max'),
        ]);

        $community = new Community();

        $community->name = $request->name;
        $community->description = $request->description;
        $community->owner_id = auth()->user()->id;

        $community->save();

        DB::table('community_members')->insert([
            'user_id' => auth()->user()->id,
            'community_id' => $community->id,
            'position'  => 'owner',
        ]);

        for ($i = 0; $i < config('site.community_create_invites'); $i++) {
            $this->createCommunityInvite($community->id);
        }

        $this->clearCache('communities');

        Alert::success(__('community.created'));
        $this->logEvent('Community Created', 'Community ' . $community->name . ' created successfully with ID ' . $community->id);

        return redirect()->route('community', $community->id);
    }

    public function show($id)
    {
        $community = $this->getCommunity($id);

        if (!$community) {
            $this->logEvent('Invalid Community', 'Community does not exist', 'warning');
            return abort(404);
        }

        return view('community.show')->with([
            'community' => $community,
        ]);
    }

    public function edit($id)
    {
        if (Gate::denies('manage-community', $id)) {
            $this->logEvent('PERMISSION DENIED', 'Attempted to access a community edit page without permissions', 'notice');
            return abort(404);
        }

        $community = $this->getCommunity($id);

        if (!$community) {
            $this->logEvent('Invalid Community', 'Community does not exist', 'warning');
            return abort(404);
        }

        return view('community.edit')->with([
            'community' => $community,
        ]);
    }

    public function update(Request $request, $id)
    {
        if (Gate::denies('manage-community', $id)) {
            $this->logEvent('PERMISSION DENIED', 'Attempted to access a community edit page without permissions', 'notice');
            return abort(404);
        }

        $community = $this->getCommunity($id);

        if (!$community) {
            $this->logEvent('Invalid Community', 'Community does not exist', 'warning');
            return abort(404);
        }

        $this->validate($request, [
            'name'  => 'string|required|min:' . config('site.input_name_min') . '|max:' . config('site.input_name_max'),
            'description' => 'string|required|min:' . config('site.input_desc_min') . '|max:' . config('site.input_desc_max'),
        ]);

        $this->logEvent('Community Update', 'Community ' . json_encode($community) . ' Updating with ' . json_encode($request->all()));

        $community->name = $request->name;
        $community->description = $request->description;

        $community->save();

        $this->clearCache('community', $community->id);

        Alert::success(__('community.updated'));
        $this->logEvent('Community Update', 'Community ID ' . $community->id . ' Updated');

        return redirect()->route('community', $community->id);
    }
}
