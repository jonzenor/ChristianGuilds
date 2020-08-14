@extends('layouts.main')
@section('content')

    <h1 class="page-header">{{ __('user.2fa_settings') }}</h1>

    <hr />

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="page-section">
        <p>{{ __('user.2fa_explain') }}</p>

        @if($data['user']->loginSecurity == null)
            <form class="form-horizontal" method="POST" action="{{ route('generate2faSecret') }}">
                {{ csrf_field() }}
                    <button type="submit" class="button-primary">
                        {{ __('user.generate_2fa') }}
                    </button>
                </div>
            </form>
        @elseif(!$data['user']->loginSecurity->google2fa_enable)
            <br />
            
            <p class="my-3">1. {!! __('user.2fa_setup_step1') !!}</p>

            <p class="my-3">
                2. {!! __('user.2fa_setup_step2') !!}<br />
                &nbsp; &nbsp; - {{ __('user.2fa_setup_alt') }} <code class="highlight">{{ $data['secret'] }}</code>
            </p>

            <p class="my-3">
                <img src="{{$data['google2fa_url'] }}" alt="{{ $data['secret'] }}" class="mx-auto">
            </p>

            <p class="my-3">
                3. {!! __('user.2fa_setup_step3') !!}
            </p>
            

            <p class="my-3">
                <form class="form-horizontal" method="POST" action="{{ route('enable2fa') }}">
                    {{ csrf_field() }}

                    <div class="form-group{{ $errors->has('verify-code') ? ' has-error' : '' }}">
                        <label for="secret" class="control-label">{{ __('user.2fa_authenticator_code') }}</label>
                        <input id="secret" type="password" class="form-field ml-4" name="secret" required>

                        @if ($errors->has('verify-code'))
                            <span class="help-block">
                            <strong>{{ $errors->first('verify-code') }}</strong>
                            </span>
                        @endif
                    </div>
                    <br />

                    <button type="submit" class="button-primary">
                        {{ __('user.enable_2fa') }}
                    </button>
                </form>
            </p>
        @elseif($data['user']->loginSecurity->google2fa_enable)
            <div class="mx-auto text-center rounded-md bg-green-300 text-green-900 border-green-600 my-8 p-3">
                {!! __('user.2fa_status_long') !!}
            </div>

            <p class="my-10">{!! __('user.2fa_disable_explain') !!}</p>

            <form class="form-horizontal" method="POST" action="{{ route('disable2fa') }}">
                {{ csrf_field() }}
                <div class="my-10">
                    <div class="form-group{{ $errors->has('current-password') ? ' has-error' : '' }}">
                        <label for="change-password" class="control-label">{{ __('user.password') }}</label>
                        <input id="current-password" type="password" class="form-field ml-4" name="current-password" required>
                        @if ($errors->has('current-password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('current-password') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="my-10">
                    <button type="submit" class="button-primary ">{{ __('user.disable_2fa') }}</button>
                    <a href="{{ route('profile', Auth::user()->id) }}" class="button-secondary">{{ __('site.cancel') }}</a>
                </div>
            </form>
        @endif
    </div>
@endsection
