@extends('layouts.main')

@section('content')
    <h1 class="page-header">{{ __('game.edit') }} {{ $game->name }} </h1>

    <hr />


    <form action="{{ route('game-update', $game->id) }}" method="post">
        @csrf

        <h2 class="section-header">{{ __('game.details') }}</h2>
        <div class="page-section">
            <div class="flex flex-wrap mb-6 md:w-1/2 lg:w-1/3">
                <label for="name" class="block text-cgwhite text-sm mb-2">
                    {{ __('game.name') }}:
                </label>

                <input id="name" type="text" class="form-field w-full @error('name')  border-red-500 @enderror" name="name" @if (old('name')) value="{{ old('name') }}" @else value="{{ $game->name }}" @endif required autocomplete="off" autofocus>

                @error('name')
                    <p class="text-red-500 text-xs italic mt-4">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="flex flex-wrap mb-6 md:w-1/2 lg:w-1/3">
                <label for="genre" class="block text-cgwhite text-sm mb-2">
                    {{ __('game.genre') }}:
                </label>

                <select name="genre" class="form-field w-full @error('genre') border-red-500 @enderror">
                    @foreach ($genres as $genre)
                        @if ($genre->short_name != "Other")
                            <option value="{{ $genre->id }}" @if (old('genre') == $genre->id) selected @elseif ($game->genre_id == $genre->id) selected @endif>{{ $genre->short_name }} - {{ $genre->name }}</option>
                        @endif
                    @endforeach
                </select>

                @error('genre')
                    <p class="text-red-500 text-xs italic mt-4">
                        {{ $message }}
                    </p>
                @enderror
            </div>

        </div>

        <div class="page-section">
            <input type="submit" value="{{ __('game.update_game') }}" class="button-primary">
            <a href="{{ route('game', $game->id) }}" class="button-secondary">{{ __('site.cancel') }}</a>
            @can('view-acp')
                <a href="{{ route('game-list') }}" class="button-secondary">{{ __('site.acp') }}</a>
            @endcan
        </div>

    </form>

@endsection
