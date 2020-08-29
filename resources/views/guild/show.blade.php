@extends('layouts.main')

@section('content')
    <h1 class="page-header">{{ $guild->name }}</h1>
    <h2 class="page-subheader">{{ $guild->game->name }} @if ($guild->game->status == "pending") <span class="bg-yellow-600 rounded-lg text-sm text-white px-4 py-1"><i class="fas fa-exclamation-circle text-lg"></i> {{ __('game.is_pending') }}</span> @endif</h2>

    <hr />

    <div class="page-section">
        <a href="{{ route('guild-edit', $guild->id) }}" class="button-primary">{{ __('guild.edit') }}</a>
    </div>


@endsection
