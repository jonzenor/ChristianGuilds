@extends('layouts.main')

@section('content')
    <h1 class="page-header">{{ __('site.search_results') }}</h1>
    <hr>

    @if ($communities->count())
        <div class="page-section">
            <h2 class="section-header">{{ __('community.communities') }}</h2>
            @foreach ($communities as $community)
                <div class="page-section">
                    <i class="fad fa-pennant px-2 text-cgwhite text-lg"></i> <a href="{{ route('community', $community->id) }}" class="link">{{ $community->name }}</a>
                </div>
            @endforeach
        </div>
    @endif

    @if ($guilds->count())
        <div class="page-section">
            <h2 class="section-header">{{ __('guild.guilds') }}</h2>
            @foreach ($guilds as $guild)
                <div class="page-section">
                    <i class="fad fa-pennant px-2 text-cgwhite text-lg"></i> <a href="{{ route('guild', $guild->id) }}" class="link">{{ $guild->name }}</a> {{ $guild->game->name }}
                </div>
            @endforeach
        </div>
    @endif

    @if ($games->count())
        <div class="page-section">
            <h2 class="section-header">{{ __('game.games') }}</h2>
            @foreach ($games as $game)
                @if ($game->status == 'confirmed')
                    <div class="page-section">
                        <i class="fad fa-game-console-handheld text-xl px-2 text-white"></i> <a href="{{ route('game', $game->id) }}" class="link">{{ $game->name }}</a>
                    </div>
                @endif
            @endforeach
        </div>
    @endif
@endsection