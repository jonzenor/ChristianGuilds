@extends('layouts.main')

@section('content')
    <h1 class="page-header">{{ $game->name }}</h1>
    @if ($game->status == "pending") <span class="bg-yellow-600 rounded-lg text-sm text-white px-4 py-1"><i class="fas fa-exclamation-circle text-lg"></i> {{ __('game.is_pending') }}</span> @endif
    <h2 class="page-subheader">{{ $game->genre->name }}</h2>

    <hr />

    <h2 class="section-header">{{ __('game.guilds')}}</h2>
    <div class="page-section">
        <ul>
            @foreach ($game->guilds as $guild)
                <li> <a href="{{ route('guild', $guild->id) }}" class="link">{{ $guild->name }}</a></li>
            @endforeach
        </ul>
    </div>

    @can('manage-games')
        <div class="page-section">
            <a href="{{ route('game-edit', $game->id) }}" class="button-primary">{{ __('game.edit') }}</a>
        </div>
    @endcan

@endsection
