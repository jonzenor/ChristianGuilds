@extends('layouts.guild')

@section('content')

    <div class="page-section">
        <p>{{ __('guild.app_explain') }}</p>
    </div>
    
    <div class="page-section">
        <h2 class="section-header">{{ __('guild.apps') }}</h2>
        <ul>
            @foreach ($guild->apps as $app)
                <li> <a href="{{ route('app-manage', $app->id) }}" class="link">{{ $app->title }}</a></li>
            @endforeach
        </ul>
    </div>

    <div class="page-section">
        <a href="{{ route('guild-app-create', $guild->id) }}" class="button-primary">{{ __('guild.app_create') }}</a>
    </div>

@endsection
