@extends('layouts.main')

@section('content')
    <h1 class="page-header">{{ $user->name }}</h1>

    <hr />


    <form action="{{ route('profile-update', $user->id) }}" method="post">
        @csrf

        <h2 class="page-subheader">{{ __('user.profile') }}</h2>
        <div class="page-section">
            <div class="flex flex-wrap mb-6 md:w-1/2 lg:w-1/3">
                <label for="name" class="block text-cgwhite text-sm mb-2">
                    {{ __('Name') }}:
                </label>

                <input id="name" type="text" class="form-field w-full @error('name')  border-red-500 @enderror" name="name" @if (old('name')) value="{{ old('name') }}" @else value="{{ $user->name }}" @endif required autocomplete="on" autofocus>

                @error('name')
                    <p class="text-red-500 text-xs italic mt-4">
                        {{ $message }}
                    </p>
                @enderror
            </div>

        </div>
        
        <hr/>
    
        <h2 class="page-subheader">{{ __('user.contact_settings') }}</h2>
        <div class="page-section">
            <div class="flex flex-wrap mb-6 md:w-1/2 lg:w-1/3">
                <label for="pushover_key" class="block text-cgwhite text-sm mb-2">
                    {{ __('user.pushover_key') }}:
                </label>

                <input id="pushover_key" type="text" class="form-field w-full @error('pushover_key')  border-red-500 @enderror" name="pushover_key" @if (old('pushover_key')) value="{{ old('pushover_key') }}" @else @if (isset($user->settings->pushover_key)) value="{{ $user->settings->pushover_key }}" @endif @endif autocomplete="off" autofocus>

                @error('pushover_key')
                    <p class="text-red-500 text-xs italic mt-4">
                        {{ $message }}
                    </p>
                @enderror
            </div>

        </div>

        <hr />

        <div class="page-section">
            <input type="submit" value="{{ __('user.update_profile') }}" class="button-primary">
        </div>
        
    </form>

@endsection