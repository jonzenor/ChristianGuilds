@extends('layouts.main')

@section('content')
    <h1 class="page-header">{{ __('game.genre_add') }}</h1>

    <hr />


    <form action="{{ route('genre-create') }}" method="post">
        @csrf

        <h2 class="page-subheader">{{ __('game.genre_details') }}</h2>
        <div class="page-section">
            <div class="flex flex-wrap mb-6 md:w-1/2 lg:w-1/3">
                <label for="name" class="block text-cgwhite text-sm mb-2">
                    {{ __('game.genre_name') }}:
                </label>

                <input id="name" type="text" class="form-field w-full @error('name')  border-red-500 @enderror" name="name" @if (old('name')) value="{{ old('name') }}" @endif required autocomplete="off" autofocus>

                @error('name')
                    <p class="text-red-500 text-xs italic mt-4">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="flex flex-wrap mb-6 md:w-1/2 lg:w-1/3">
                <label for="short_name" class="block text-cgwhite text-sm mb-2">
                    {{ __('game.genre_short_name') }}:
                </label>

                <input id="short_name" type="text" class="form-field w-full @error('short_name')  border-red-500 @enderror" name="short_name" @if (old('short_name')) value="{{ old('short_name') }}" @endif required autocomplete="off" autofocus>

                @error('short_name')
                    <p class="text-red-500 text-xs italic mt-4">
                        {{ $message }}
                    </p>
                @enderror
            </div>

        </div>

        <div class="page-section">
            <input type="submit" value="{{ __('game.update_genre') }}" class="button-primary">
            <a href="{{ route('genre-list') }}" class="button-secondary">{{ __('site.cancel') }}</a>
        </div>
        
    </form>

@endsection