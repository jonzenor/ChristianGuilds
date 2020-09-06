@extends('layouts.main')

@section('content')
    <h1 class="page-header">{{ __('game.edit_realm') }} {{ $realm->game->name }} - {{ $realm->name }}</h1>

    <hr />


    <form action="{{ route('game-realm-update', $realm->id) }}" method="post">
        @csrf

        <div class="page-section">
            <h2 class="section-header">{{ __('game.realm_details') }}</h2>
            <div class="flex flex-wrap mb-6 md:w-1/2 lg:w-1/3">
                <label for="name" class="block text-cgwhite text-sm mb-2">
                    {{ __('game.realm_name') }}:
                </label>

                <input id="name" type="text" class="form-field w-full @error('name') border-red-500 @enderror" name="name" @if (old('name')) value="{{ old('name') }}" @else value="{{ $realm->name }}" @endif required autocomplete="off" autofocus>

                @error('name')
                    <p class="text-red-500 text-xs italic mt-4">
                        {{ $message }}
                    </p>
                @enderror
            </div>
        </div>

        <div class="page-section">        
            <label for="type" class="block text-cgwhite text-sm mb-2">
                {{ __('game.realm_type') }}:
            </label>

            <input type="text" name="type" class="form-field @error('type') border-red-500 @enderror" @if (old('type')) value="{{ old('type') }}" @else value="{{ $realm->type }}" @endif id="type" placeholder="i.e. PvE">

            @error('type')
                <p class="text-red-500 text-xs italic mt-4">
                    {{ $message }}
                </p>
            @enderror
        </div>


        <div class="page-section">
            <input type="submit" value="{{ __('game.update_realm') }}" class="button-primary">
            <a href="{{ route('game-edit', $realm->game->id) }}" class="button-secondary">{{ __('site.cancel') }}</a>
            @can('view-acp')
                <a href="{{ route('game-list') }}" class="button-secondary">{{ __('site.acp') }}</a>
            @endcan
        </div>

    </form>

@endsection
