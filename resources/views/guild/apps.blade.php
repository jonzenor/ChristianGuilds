@extends('layouts.guild')

@section('content')

    <div class="page-section">
        <p>{{ __('guild.app_explain') }}</p>
    </div>
    
    <div class="page-section">
        <ul>
            @foreach ($guild->apps as $app)
                <li> <a href="{{ route('app-manage', $app->id) }}" class="link">{{ $app->title }}</a></li>
            @endforeach
        </ul>
    </div>

    Create apps button

    Edit Apps

    <div class="page-section">
        <a href="{{ route('guild-app-create', $guild->id) }}" class="button-primary">{{ __('guild.app_create') }}</a>
    </div>

@endsection
