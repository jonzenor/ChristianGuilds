@extends('layouts.main')

@section('content')
    <h1 class="page-header">{{ $guild->name }}</h1>
    <h2 class="page-subheader">{{ $guild->game->name }} @if ($guild->game->status == "pending") <span class="bg-yellow-600 rounded-lg text-sm text-white px-4 py-1"><i class="fas fa-exclamation-circle text-lg"></i> {{ __('game.is_pending') }}</span> @endif</h2>

    <hr />

    <form action="{{ route('guild-update', $guild->id) }}" method="post">
        @csrf

        <h2 class="page-subheader">{{ __('guild.details') }}</h2>
        <div class="page-section">
            <div class="flex flex-wrap mb-6 md:w-1/2 lg:w-1/3">
                <label for="name" class="block text-cgwhite text-sm mb-2">
                    {{ __('Name') }}:
                </label>

                <input id="name" type="text" class="form-field w-full @error('name')  border-red-500 @enderror" name="name" @if (old('name')) value="{{ old('name') }}" @else value="{{ $guild->name }}" @endif required autocomplete="on" autofocus>

                @error('name')
                    <p class="text-red-500 text-xs italic mt-4">
                        {{ $message }}
                    </p>
                @enderror
            </div>

        </div>

        <div class="page-section">
            <input type="submit" value="{{ __('guild.update') }}" class="button-primary">
            <a href="{{ route('guild', $guild->id) }}" class="button-secondary">{{ __('site.cancel') }}</a>
        </div>
    </form>

@endsection
