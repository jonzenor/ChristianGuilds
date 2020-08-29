<?php

use App\Game;
use Illuminate\Support\Facades\Cache;

function getPendingGamesCount()
{
    return Cache::remember('Games:pending:count', 1440, function () {
        return Game::where('status', '=', 'pending')->get()->count();
    });
}
