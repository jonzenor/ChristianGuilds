@extends('layouts.main')

@section('content')
    <h1 class="page-header">{{ $guild->name }}</h1>
    <h2 class="page-subheader"><a href="{{ route('game', $guild->game_id) }}" class="link">{{ $guild->game->name }}</a> @if ($guild->game->status == "pending") <span class="bg-yellow-600 rounded-lg text-sm text-white px-4 py-1"><i class="fas fa-exclamation-circle text-lg"></i> {{ __('game.is_pending') }}</span> @endif</h2>

    <hr />

    <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3">
        <div class="page-section">
            <form action="{{ route('guild-update', $guild->id) }}" method="post">
                @csrf

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

                <br /><br />

                <label for="description" class="block text-cgwhite text-sm mb-2">
                    {{ __('guild.description') }}
                </label>
    
                <textarea name="description" id="description" class="form-field w-full">@if (old('description')){!! old('description') !!}@else{!! $guild->description !!}@endif</textarea>
    
                @error('description')
                    <p class="text-red-500 text-xs italic mt-4">
                        {{ $message }}
                    </p>
                @enderror

                <br />

                @if ($guild->game->realms->count())
                    <select name="server_id" id="server" class="form-field">
                        <option value="0">{{ __('guild.no_server_selected') }}</option>
                        @foreach ($guild->game->realms as $realm)
                            @foreach ($realm->servers as $server)
                                <option value="{{ $server->id }}" @if ($guild->server_id == $server->id) selected @endif>{{ $realm->name }} [{{ $realm->type }}] - {{ $server->name }}</option>
                            @endforeach
                        @endforeach
                    </select>

                    @error('server_id')
                        <p class="text-red-500 text-xs italic mt-4">
                            {{ $message }}
                        </p>
                    @enderror

                @endif

                <div class="my-4">

                    <label for="server_name" class="block text-cgwhite text-sm mb-2">
                        {{ __('guild.server_name') }}
                    </label>

                    <input name="server_name" id="server_name" class="form-field w-full" value="@if (old('server_name')){{ old('server_name') }}@else{{ $guild->server_name }}@endif">

                    @error('server_name')
                        <p class="text-red-500 text-xs italic mt-4">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <br />

                <input type="submit" value="{{ __('guild.update') }}" class="button-primary">
                <a href="{{ route('guild', $guild->id) }}" class="button-secondary">{{ __('site.cancel') }}</a>

            </form>
        </div>

        <div class="page-section">
            <h3 class="section-header">{{ __('guild.community_membership') }}</h3>

            @if (!$guild->community_id)
                <h4 class="section-subheader">{{ __('guild.community_join') }}</h4>

                <div><p>{!! __('guild.community_explain') !!}</p></div>

                <br />

                <form action="{{ route('community-join', $guild->id) }}" method="post">
                    @csrf

                    <div>
                        <label for="invite_code" class="block text-cgwhite text-sm mb-2">
                            {{ __('guild.invite_code') }}
                        </label>

                        <input name="invite_code" id="invite_code" class="form-field w-full" placeholder="{{ __('guild.invite_placeholder') }}">

                        @error('invite_code')
                            <p class="text-red-500 text-xs italic mt-4">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <br />

                    <input type="submit" value="{{ __('guild.join_community') }}" class="button-primary">

                </form>
            @endif
        </div>

    </div>


@endsection
