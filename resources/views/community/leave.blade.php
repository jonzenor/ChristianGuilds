@extends('layouts.main')

@section('content')
    <h1 class="page-header">{{ __('community.leave') }}</h1>
    
    <hr />

    <div class="page-section">
        <h3 class="section-header">{{ $guild->community->name }}</h3>
    </div>

    <div class="page-section">
        <p>{!! __('community.leave_confirm', ['community' => $guild->community->name]) !!}</p>
    </div>

    <div class="page-section">
        <form action="{{ route('community-leave-confirm', $guild->id) }}" method="post">
            @csrf
            
            <input type="submit" class="button-primary" value="{{ __('community.leave_confirm_button') }}">
            <a href="{{ route('guild-edit', $guild->id) }}" class="button-secondary">{{ __('site.nevermind') }}</a>
        </form>
    </div>

@endsection
