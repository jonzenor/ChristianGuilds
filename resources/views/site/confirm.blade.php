@extends('layouts.main')

@section('content')
    <h1 class="page-header">{{ __('site.confirm_action') }}</h1>
    <hr>
    <h2 class="page-subheader">{{ $confirm_data['header'] }}</h2>
    <div class="page-section">
        {!! $confirm_data['body'] !!}
    </div>
    <div class="page-section">
        <form action="{{ $confirm_data['action'] }}" method="post">
            @csrf

            <input type="submit" value="{{ __('user.confirm_remove_button') }}" class="button-primary">
            <a href="{{ $confirm_data['cancel'] }}" class="button-secondary">{{ __('site.cancel') }}</a>
        </form>
    </div>
@endsection