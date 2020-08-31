@extends('layouts.main')

@section('content')
    <h1 class="page-header">{{ __('game.pending') }}</h1>

    <hr>

    <div class="page-section">

        @foreach ($games as $game)
            <h2 class="text-cggreen-400 text-2xl my-4">{{ $game->guild->name }}</h2>

            <p><span class="font-bold mr-4">{{ __('game.game') }}:</span> {{ $game->name }}</p>
            <p><span class="font-bold mr-4">{{ __('game.genre') }}:</span> <span class="text-cgpink-600">{{ $game->genre->short_name }}</span> {{ $game->genre->name }}</p>

            <div class="grid grid-cols-1 md:grid-cols-2 m-8">
                <div class="w-full p-3">

                    @if ($game->genre->short_name == "Other")

                        <div class="flex flex-col break-words bg-cggray-700 border border-2 rounded shadow-md">

                            <div class="font-semibold bg-cggray-900 text-purple-500 py-3 px-6 mb-0 text-xl text-center">
                                {{ __('game.set_genre') }}
                            </div>

                            <div class="w-full p-6">
                                <p class="text-cgwhite">
                                    {{ __('game.set_genre_message') }}
                                </p>
                            </div>

                            <form action="{{ route('game-set-pending-genre', $game->id) }}" method="post">
                                @csrf

                                <div class="w-full p-4">
                                    <label for="genre" class="block text-cgwhite text-sm mb-2">
                                        {{ __('game.genre') }}:
                                    </label>

                                    <select name="genre" class="form-field w-full @error('genre') border-red-500 @enderror">
                                        <option value="0">==== Create New Genre... ====</option>
                                        @foreach ($genres as $genre)
                                            @if ($genre->short_name != "Other")
                                                <option value="{{ $genre->id }}">{{ $genre->short_name }} - {{ $genre->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>

                                    @error('genre')
                                        <p class="text-red-500 text-xs italic mt-4">
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div class="w-full p-4">
                                    <label for="name" class="block text-cgwhite text-sm mb-2">
                                        {{ __('game.genre_name') }}:
                                    </label>

                                    <p class="text-cgwhite">
                                        <input type="text" name="name" class="form-field w-full" placeholder=" {{ __('game.new_genre_placeholder') }}">
                                    </p>

                                    @error('name')
                                        <p class="text-red-500 text-xs italic mt-4">
                                            {{ $message }}
                                        </p>
                                    @enderror

                                </div>

                                <div class="w-full p-4">
                                    <label for="short_name" class="block text-cgwhite text-sm mb-2">
                                        {{ __('game.genre_short_name') }}:
                                    </label>

                                    <p class="text-cgwhite">
                                        <input type="text" name="short_name" class="form-field w-full" >
                                    </p>

                                    @error('short_name')
                                        <p class="text-red-500 text-xs italic mt-4">
                                            {{ $message }}
                                        </p>
                                    @enderror

                                </div>

                                <div class="w-full p-4">
                                    <input type="submit" class="button-primary" value="{{ __('game.set_genre') }}">
                                </div>
                            </form>
                        </div>

                    @else

                        <form action="{{ route('game-approve-pending', $game->id) }}" method="post">
                            @csrf

                            <div class="flex flex-col break-words bg-cggray-700 border border-2 rounded shadow-md">

                                <div class="font-semibold bg-cggray-900 text-purple-500 py-3 px-6 mb-0 text-xl text-center">
                                    {{ __('game.accept_pending') }}
                                </div>

                                <div class="w-full p-6">
                                    <p class="text-cgwhite">
                                        {{ __('game.accept_message') }}
                                    </p>
                                </div>

                                <div class="w-full p-4">
                                    <label for="name" class="block text-cgwhite text-sm mb-2">
                                        {{ __('game.game') }}:
                                    </label>

                                    <p class="text-cgwhite">
                                        <input type="text" name="name" class="form-field w-full" value="{{ $game->name }}">
                                    </p>

                                    @error('name')
                                        <p class="text-red-500 text-xs italic mt-4">
                                            {{ $message }}
                                        </p>
                                    @enderror

                                </div>

                                <div class="w-full p-4">
                                    <label for="genre" class="block text-cgwhite text-sm mb-2">
                                        {{ __('game.genre') }}:
                                    </label>

                                    <select name="genre" class="form-field w-full @error('genre') border-red-500 @enderror">
                                        @foreach ($genres as $genre)
                                            @if ($genre->short_name != "Other")
                                                <option value="{{ $genre->id }}" @if ($genre->id == $game->genre_id) selected @endif>{{ $genre->short_name }} - {{ $genre->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>

                                    @error('genre')
                                        <p class="text-red-500 text-xs italic mt-4">
                                            {{ $message }}
                                        </p>
                                    @enderror

                                </div>

                                <div class="w-full p-4">
                                    <input type="submit" class="button-primary" value="{{ __('game.accept_game') }}">
                                </div>
                            </div>
                        </form>
                    @endif
                </div>

                <div class="w-full p-3">

                    <form action="{{ route('game-reject-pending', $game->id) }}" method="post">
                        @csrf

                        <div class="flex flex-col break-words bg-cggray-700 border border-2 rounded shadow-md">

                            <div class="font-semibold bg-cggray-900 text-purple-500 py-3 px-6 mb-0 text-xl text-center">
                                {{ __('game.existing_game') }}
                            </div>

                            <div class="w-full p-6">
                                <p class="text-cgwhite">
                                    {{ __('game.existing_message') }}
                                </p>
                            </div>

                            <div class="w-full p-4">
                                <label for="game" class="block text-cgwhite text-sm mb-2">
                                    {{ __('game.game') }}:
                                </label>

                                <select name="game" class="form-field w-full @error('game') border-red-500 @enderror">
                                    @foreach ($listGames as $listGame)
                                        <option value="{{ $listGame->id }}">{{ $listGame->name }}</option>
                                    @endforeach
                                </select>

                                @error('game')
                                    <p class="text-red-500 text-xs italic mt-4">
                                        {{ $message }}
                                    </p>
                                @enderror

                            </div>

                            <div class="w-full p-4">
                                <input type="submit" class="button-primary" value="{{ __('game.reject_game') }}">
                            </div>
                        </div>
                    </form>

                </div>

            </div>

            <hr />
        @endforeach

    </div>

    <div class="page-section">
        <a href="{{ route('acp') }}" class="button-secondary">{{ __('site.acp') }}</a>
    </div>
@endsection
