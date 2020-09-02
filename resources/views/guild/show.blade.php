@extends('layouts.main')

@section('content')
    <h1 class="page-header">{{ $guild->name }}</h1>
    <h2 class="page-subheader"><a href="{{ route('game', $guild->game_id) }}" class="link">{{ $guild->game->name }}</a> @if ($guild->game->status == "pending") <span class="bg-yellow-600 rounded-lg text-sm text-white px-4 py-1"><i class="fas fa-exclamation-circle text-lg"></i> {{ __('game.is_pending') }}</span> @endif</h2>

    <hr />

    @can('manage-guild', $guild->id)
        <div class="page-section">
            <a href="{{ route('guild-edit', $guild->id) }}" class="button-primary">{{ __('guild.edit') }}</a>
        </div>
    @endcan

@endsection
