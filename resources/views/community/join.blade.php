@extends('layouts.main')

@section('content')
    <h1 class="page-header">{{ __('community.join') }}</h1>
    
    <hr />

    <div class="page-section">
        <h3 class="section-header">{{ $community->name }}</h3>
        <p>{{ __('community.description') }}</p>
        <p>{!! $community->description !!}</p>
    </div>

    <div class="page-section">
        <p>{!! __('community.join_confirm', ['community' => $community->name]) !!}</p>
    </div>

    <div class="page-section">
        <form action="{{ route('community-join-confirm', $guild->id) }}" method="post">
            @csrf
            
            <input type="hidden" name="code" value="{{ $invite->code }}">

            <input type="submit" class="button-primary" value="{{ __('community.join_confirm_button') }}">
            <a href="{{ route('guild-edit', $guild->id) }}" class="button-secondary">{{ __('site.nevermind') }}</a>
        </form>
    </div>


@endsection
