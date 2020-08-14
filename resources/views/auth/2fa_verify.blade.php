@extends('layouts.main')
@section('content')

    <h1 class="page-header">{{ __('user.2fa') }}</h1>

    <hr />

    <div class="page-section">
        <p class="my-3">{{ __('user.2fa_explain') }}</p>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <p class="my-3">
            <span class="highlight">{{ __('user.2fa_enter_pin') }}</span>
        </p>


        <form class="form-horizontal" action="{{ route('2faVerify') }}" method="POST">
            {{ csrf_field() }}
            <div class="my-4">
                <div class="form-group{{ $errors->has('one_time_password-code') ? ' has-error' : '' }}">
                    <label for="one_time_password" class="control-label">{{ __('user.2fa_otp') }}</label>
                    <input id="one_time_password" name="one_time_password" class="form-field ml-4"  type="text" required/>
                </div>
            </div>
            <div class="my-4">
                <button class="button-primary" type="submit">{{ __('user.2fa_authenticate') }}</button>
            </div>
        </form>

    </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
