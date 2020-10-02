@extends('layouts.guild')

@section('content')
    <div class="page-section">
        <h2 class="section-header">{{ __('app.edit') }} - {{ $app->title }}</h2>
    </div>
    <form action="{{ route('app-update', $app->id) }}" method="post">
        @csrf

        <div class="flex flex-wrap mb-6 md:w-1/2 lg:w-1/3">
            <label for="name" class="form-label">
                {{ __('app.name') }}
            </label>

            <input id="name" type="text" name="name" class="form-field w-full @error('name')  border-red-500 @enderror" @if (old('name')) value="{{ old('name') }}" @else value="{{ $app->title }}" @endif required autocomplete="off" autofocus>

            @error('name')
                <p class="text-red-500 text-xs italic mt-4">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div class="flex flex-wrap mb-6 md:w-1/2 lg:w-1/3">
            <label for="visibility" class="form-label">
                {{ __('app.visibility') }}
            </label>

            <select name="visibility" class="form-field w-full @error('visibility') border-red-500 @enderror">
                <option value="private" @if (old('visibility') && old('visibility') == "private") selected @elseif ($app->visibility == "private") selected @endif>{{ __('app.private') }}</option>
                <option value="public" @if (old('visibility') && old('visibility') == "public") selected @elseif ($app->visibility == "public") selected @endif>{{ __('app.public') }}</option>
            </select>

            @error('visibility')
                <p class="text-red-500 text-xs italic mt-4">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div class="flex flex-wrap mb-6 md:w-1/2 lg:w-1/3">
            <label for="promote_to" class="form-label">
                {{ __('app.promote_to') }}
            </label>
            @if ($guild->role_type == "simple")
                <select name="promote_to" class="form-field w-full @error('promote_to') border-red-500 @enderror">
                    <option value="1" @if (old('promote_to') && old('promote_to') == "1") selected @elseif ($app->promotion_rank == '1') selected @endif>{{ __('guild.member') }}</option>
                    <option value="2" @if (old('promote_to') && old('promote_to') == "2") selected @elseif ($app->promotion_rank == '2') selected @endif>{{ __('guild.manager') }}</option>
                </select>
            @endif

            @error('promote_to')
                <p class="text-red-500 text-xs italic mt-4">
                    {{ $message }}
                </p>
            @enderror

            <p class="text-gray-400 text-xs">{{ __('app.promote_to_explain') }}</p>

        </div>

        <div class="flex flex-wrap mb-6 md:w-1/2 lg:w-1/3">
            <input type="submit" value="{{ __('app.update') }}" class="button-primary">
            <a href="{{ route('guild-apps', $guild->id) }}" class="button-secondary">{{ __('site.cancel') }}</a>
        </div>

    </form>
@endsection
