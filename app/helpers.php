<?php

use App\Game;
use App\Page;
use Illuminate\Support\Facades\Cache;

function getPendingGamesCount()
{
    return Cache::remember('Games:pending:count', 1440, function () {
        return Game::where('status', '=', 'pending')->get()->count();
    });
}

function getGuildPage($guild, $slug)
{
    return Cache::remember('Guild:' . $guild . ':slug:' . $slug, 1440, function () use ($guild, $slug) {
        $page =  Page::where('guild_id', '=', $guild)->where('slug', '=', $slug)->first();

        if (!$page) {
            $page = new Page();
            $page->content = "404 Page Not Found";
        }

        return $page->content;
    });
}
