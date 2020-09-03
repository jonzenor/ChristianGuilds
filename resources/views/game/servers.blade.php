@extends('layouts.main')

@section('content')
    <h1 class="page-header">{{ $game->name }}</h1>
    @if ($game->status == "pending") <span class="bg-yellow-600 rounded-lg text-sm text-white px-4 py-1"><i class="fas fa-exclamation-circle text-lg"></i> {{ __('game.is_pending') }}</span> @endif
    <h2 class="page-subheader">{{ $game->genre->name }}</h2>

    <hr />

    <h2 class="section-header">{{ __('game.servers')}}</h2>
    <div class="page-section">
        <ul>
            @foreach ($game->realms as $realm)
                <li> {{ $realm->name }} - {{ $realm->type }}</li>
            @endforeach
        </ul>
    </div>

    <div class="page-section">&nbsp;</div>


    <h2 class="section-header">{{ __('game.add_realm')}}</h2>
    <form action="{{ route('game-realm-add', $game->id) }}" method="post">
        @csrf

        <div class="page-section">        
            <label for="name" class="block text-cgwhite text-sm mb-2">
                {{ __('game.realm_name') }}:
            </label>

            <input type="text" name="name" class="form-field" id="name" value="Realm">

            @error('name')
                <p class="text-red-500 text-xs italic mt-4">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div class="page-section">        
            <label for="type" class="block text-cgwhite text-sm mb-2">
                {{ __('game.realm_type') }}:
            </label>

            <input type="text" name="type" class="form-field" id="type" placeholder="i.e. PvE">

            @error('type')
                <p class="text-red-500 text-xs italic mt-4">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div class="page-section">
            <input type="submit" value="{{ __('game.add_realm') }}" class="button-primary">
            <a href="{{ route('game', $game->id) }}" class="button-secondary">{{ __('site.cancel') }}</a>
        </div>

    </form>

@endsection
