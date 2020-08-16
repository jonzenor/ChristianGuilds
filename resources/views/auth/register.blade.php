@extends('layouts.main')

@section('content')

    <style>
        .grecaptcha-badge { visibility: visible; }
    </style>

    <div class="container mx-auto">
        <div class="flex flex-wrap justify-center">
            <div class="w-full max-w-sm">
                <div class="flex flex-col break-words bg-cggray-700 border-cggray-500 border-2 rounded shadow-md">

                    <div class="font-semibold bg-cggray-900 text-cgwhite py-3 px-6 mb-0">
                        {{ __('Register') }}
                    </div>

                    <form class="w-full p-6" method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="flex flex-wrap mb-6">
                            <label for="name" class="block text-cgwhite text-sm mb-2">
                                {{ __('Name') }}:
                            </label>

                            <input id="name" type="text" class="form-field w-full @error('name')  border-red-500 @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                            @error('name')
                                <p class="text-red-500 text-xs italic mt-4">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="flex flex-wrap mb-6">
                            <label for="email" class="block text-cgwhite text-sm mb-2">
                                {{ __('E-Mail Address') }}:
                            </label>

                            <input id="email" type="email" class="form-field w-full @error('email') border-red-500 @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                            @error('email')
                                <p class="text-red-500 text-xs italic mt-4">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="flex flex-wrap mb-6">
                            <label for="password" class="block text-cgwhite text-sm mb-2">
                                {{ __('Password') }}:
                            </label>

                            <input id="password" type="password" class="form-field w-full @error('password') border-red-500 @enderror" name="password" required autocomplete="new-password">

                            @error('password')
                                <p class="text-red-500 text-xs italic mt-4">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="flex flex-wrap mb-6">
                            <label for="password-confirm" class="block text-cgwhite text-sm mb-2">
                                {{ __('Confirm Password') }}:
                            </label>

                            <input id="password-confirm" type="password" class="form-field w-full" name="password_confirmation" required autocomplete="new-password">
                        </div>

                        <input type="hidden" name="recaptcha" id="recaptcha">

                        <div class="flex flex-wrap">
                            <input type="submit" class="button-primary" value="{{ __('Register') }}">

                            <p class="w-full text-xs text-center text-cgwhite mt-8 -mb-4">
                                {{ __('Already have an account?') }}
                                <a class="link" href="{{ route('login') }}">
                                    {{ __('Login') }}
                                </a>
                            </p>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
