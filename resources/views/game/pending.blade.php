@extends('layouts.main')

@section('content')
    <h1 class="page-header">{{ __('game.pending') }}</h1>

    <hr>

    {{--
    <div class="page-section">

        @foreach ($games as $game)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
                <div>{{ $game->guild->name }}</div>
                <div>{{ $game->name }}</div>
                <div>{{ $game->genre->name }}</div>
            </div>
        @endforeach

    </div>
    --}}

    <div class="page-section">

        <table>
            <thead>
                <tr>
                    <th class="px-4">{{ __('guild.name') }}</th>
                    <th class="px-4">{{ __('game.game') }}</th>
                    <th class="px-4">{{ __('game.game_genre') }}</th>
                </tr>
            </thead>
            @foreach ($games as $game)
                <tr class="my-3">
                    <td class="px-4">{{ $game->guild->name }}</td>
                    <td class="px-4">{{ $game->name }}</td>
                    <td class="px-4">{{ $game->genre->name }}</td>
                </tr>
            @endforeach
        </table>
    </div>

    <div class="page-section">
        <a href="{{ route('acp') }}" class="button-secondary">{{ __('site.acp') }}</a>
    </div>
@endsection