<?php

namespace App\Http\Controllers;

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
}
