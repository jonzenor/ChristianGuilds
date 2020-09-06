@extends('layouts.main')

@section('content')
    <h1 class="page-header">{{ __('game.edit_server') }} {{ $server->realm->game->name }} - {{ $server->realm->name }} - {{ $server->name }}</h1>

    <hr />


    <form action="{{ route('game-server-update', $server->id) }}" method="post">
        @csrf

        <div class="page-section">
            <h2 class="section-header">{{ __('game.server_details') }}</h2>
            <div class="flex flex-wrap mb-6 md:w-1/2 lg:w-1/3">
                <label for="name" class="block text-cgwhite text-sm mb-2">
                    {{ __('game.server_name') }}:
                </label>

                <input id="name" type="text" class="form-field w-full @error('name') border-red-500 @enderror" name="name" @if (old('name')) value="{{ old('name') }}" @else value="{{ $server->name }}" @endif required autocomplete="off" autofocus>

                @error('name')
                    <p class="text-red-500 text-xs italic mt-4">
                        {{ $message }}
                    </p>
                @enderror
            </div>
        </div>

        <div class="page-section">
            <input type="submit" value="{{ __('game.update_server') }}" class="button-primary">
            <a href="{{ route('game-edit', $server->realm->game->id) }}" class="button-secondary">{{ __('site.cancel') }}</a>
            @can('view-acp')
                <a href="{{ route('game-list') }}" class="button-secondary">{{ __('site.acp') }}</a>
            @endcan
        </div>

    </form>

@endsection
