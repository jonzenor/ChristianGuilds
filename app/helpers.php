<?php

use App\Game;
use Illuminate\Support\Facades\Cache;

function getPendingGamesCount()
{
    return Cache::rememberForever('Games:pending:count', function () {
        return Game::where('status', '=', 'pending')->get()->count();
    });
}
