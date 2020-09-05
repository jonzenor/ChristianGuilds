@extends('layouts.main')

@section('content')
    <h1 class="page-header">{{ $guild->name }}</h1>
    <h2 class="page-subheader"><a href="{{ route('game', $guild->game_id) }}" class="link">{{ $guild->game->name }}</a> @if ($guild->game->status == "pending") <span class="bg-yellow-600 rounded-lg text-sm text-white px-4 py-1"><i class="fas fa-exclamation-circle text-lg"></i> {{ __('game.is_pending') }}</span> @endif</h2>

    <hr />

    <form action="{{ route('guild-update', $guild->id) }}" method="post">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3">
            <div class="page-section">
                <h3 class="section-header">{{ __('guild.details') }}</h3>
                <label for="name" class="block text-cgwhite text-sm mb-2">
                    {{ __('Name') }}:
                </label>

                <input id="name" type="text" class="form-field w-full @error('name')  border-red-500 @enderror" name="name" @if (old('name')) value="{{ old('name') }}" @else value="{{ $guild->name }}" @endif required autocomplete="on" autofocus>

                @error('name')
                    <p class="text-red-500 text-xs italic mt-4">
                        {{ $message }}
                    </p>
                @enderror

                <h3 class="section-header">{{ __('guild.server') }}</h3>
                @if ($guild->game->realms->count())
                    <select name="server_id" id="server" class="form-field">
                        <option value="0">No Server Selected</option>
                        @foreach ($guild->game->realms as $realm)
                            @foreach ($realm->servers as $server)
                                <option value="{{ $server->id }}" @if ($guild->server_id == $server->id) selected @endif>{{ $realm->name }} [{{ $realm->type }}] - {{ $server->name }}</option>
                            @endforeach
                        @endforeach
                    </select>
                @else
                    <input name="server_name" id="server" class="form-field" value="{{ $guild->server_name }}">

                @endif

            </div>

        </div>




        <div class="page-section">
            <input type="submit" value="{{ __('guild.update') }}" class="button-primary">
            <a href="{{ route('guild', $guild->id) }}" class="button-secondary">{{ __('site.cancel') }}</a>
        </div>
    </form>

@endsection
