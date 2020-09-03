@extends('layouts.main')

@section('content')
    <h1 class="page-header">{{ __('game.add') }}</h1>

    <hr />


    <form action="{{ route('game-create') }}" method="post">
        @csrf

        <h2 class="section-header">{{ __('game.details') }}</h2>
        <div class="page-section">
            <div class="flex flex-wrap mb-6 md:w-1/2 lg:w-1/3">
                <label for="name" class="block text-cgwhite text-sm mb-2">
                    {{ __('game.name') }}:
                </label>

                <input id="name" type="text" class="form-field w-full @error('name')  border-red-500 @enderror" name="name" @if (old('name')) value="{{ old('name') }}" @endif required autocomplete="off" autofocus>

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
                        <option value="{{ $genre->id }}" @if (old('genre') == $genre->id) selected @endif>{{ $genre->short_name }} - {{ $genre->name }}</option>
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
            <input type="submit" value="{{ __('game.add') }}" class="button-primary">
            <a href="{{ route('game-list') }}" class="button-secondary">{{ __('site.cancel') }}</a>
        </div>

    </form>

@endsection
