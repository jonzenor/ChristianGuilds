@extends('layouts.guild')

@section('content')

    <div class="page-section">
        <p>{{ __('guild.app_explain') }}</p>
    </div>
    List of apps

    Create apps button

    Edit Apps

    <div class="page-section">
        <a href="{{ route('guild-app-create', $guild->id) }}" class="button-primary">{{ __('guild.app_create') }}</a>
    </div>

@endsection
