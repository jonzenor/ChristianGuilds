@extends('layouts.main')

@section('content')
    <h1 class="page-header">{{ __('game.list') }}</h1>

    <hr>

    <div class="page-section">
        <table>
            <thead>
                <tr>
                    <th class="px-4">{{ __('game.id') }}</th>
                    <th class="px-4">{{ __('game.name') }}</th>
                    <th class="px-4">{{ __('game.genre') }}</th>
                </tr>
            </thead>
            @foreach ($games as $game)
                <tr class="my-3">
                    <td class="px-4">{{ $game->id }}</td>
                    <td class="px-4">{{ $game->name }}</td>
                    <td class="px-4">{{ $game->genre->short_name }}</td>
                </tr>
            @endforeach
        </table>
    </div>

    <div class="w-1/4 mx-auto text-center">
        {{ $games->links() }}
    </div>

    <div class="page-section">
        <a href="{{ route('game-add') }}" class="button-primary">{{ __('game.add') }}</a>
        <a href="{{ route('acp') }}" class="button-secondary">{{ __('site.acp') }}</a>
    </div>
@endsection