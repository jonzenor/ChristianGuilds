@extends('layouts.guild')

@section('content')

    <h2 class="section-header">{{ $submission->app->title }}</h2>

    <div class="page-section">
        {{ __('site.user') }}: <a href="{{ route('profile', $submission->user->id) }}" class="link">{{ $submission->user->name }}</a><br />
        {{ __('app.date_submitted') }}: {{ $submission->created_at }}
    </div>

    <div class="page-section">
        <h3 class="section-subheader">{{ __('app.answers') }}</h3>

        <p>
            <span class="text-lg text-cgblue-500">{{ __('site.name') }}</span><br />
            {{ $submission->name }}
        </p>

        @foreach ($questions as $question)
            <p>
                <span class="text-lg text-cgblue-500">{{ $question->text }}</span><br />
                {{ $answers[$question->id] }}
            </p>
        @endforeach
    </div>

@endsection
