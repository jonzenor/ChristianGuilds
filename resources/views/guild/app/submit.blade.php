@extends('layouts.guild')

@section('content')

    <h2 class="section-header">{{ $app->title }}</h2>

    <form action="{{ route('app-submit-answers', $app->id) }}" method="post">
        @csrf

        <div class="page-section">
            <label for="name" class="form-label">{{ __('site.name') }}</label>

            <input id="name" name="name" class="form-field w-full" @if(old('name')) value="{{ old('name') }}"@endif required>

            @error('name')
                <p class="text-red-500 text-xs italic mt-4">
                    {{ $message }}
                </p>
            @enderror
        </div>

        @foreach ($app->questions as $question)
            <div class="page-section">
                <label for="question-{{ $question->id }}" class="form-label">{{ $question->number }}. {{ $question->text }}</label>

                <input id="question-{{ $question->id }}" name="question-{{ $question->id }}" class="form-field w-full" @if(old('question-' . $question->id)) value="{{ old('question-' . $question->number) }}"@endif required>

                @error('question-' . $question->id)
                    <p class="text-red-500 text-xs italic mt-4">
                        {{ $message }}
                    </p>
                @enderror
            </div>
        @endforeach

        <div class="page-section flex flex-wrap mb-6 md:w-1/2 lg:w-1/3">
            <input type="submit" value="{{ __('app.submit') }}" class="button-primary">
            <a href="{{ route('guild-apps', $guild->id) }}" class="button-secondary">{{ __('site.cancel') }}</a>
        </div>

    </form>
@endsection
