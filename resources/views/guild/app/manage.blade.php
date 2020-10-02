@extends('layouts.guild')

@section('content')

    <div class="page-section">
        <h2 class="section-header">{{ $app->title }}</h2>
        <p>{{ __('app.visibility') }}: {{ __('app.' . $app->visibility) }}</p>
        <p>{{ __('app.promote_to') }}: 
            @if ($guild->role_type == "simple")
                @if ($app->promotion_rank == 1) {{ __('guild.member') }} @endif
                @if ($app->promotion_rank == 2) {{ __('guild.manager') }} @endif
                @if ($app->promotion_rank == 3) {{ __('guild.owner') }} @endif
            @endif
        </p>
        <p>
            <a href="{{ route('app-edit', $app->id) }}" class="button-primary">{{ __('app.edit') }}</a>
        </p>
    </div>

    <div class="page-section">
        <h3 class="section-subheader">{{ __('app.questions') }}</h3>

        @foreach($app->questions as $question)
            <p>{{ $question->number }}. {{ $question->text }}</p>
        @endforeach
    </div>

    <form action="{{ route('app-question-add', $app->id) }}" method="post">
        @csrf

        <div class="flex flex-wrap mb-6 md:w-1/2 lg:w-1/3">
            <label for="name" class="form-label">
                {{ __('app.question') }}
            </label>

            <input id="text" type="text" name="text" class="form-field w-full @error('text')  border-red-500 @enderror" @if (old('text')) value="{{ old('text') }}" @endif required autocomplete="off" autofocus>

            @error('text')
                <p class="text-red-500 text-xs italic mt-4">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div class="flex flex-wrap mb-6 md:w-1/2 lg:w-1/3">
            <input type="submit" value="{{ __('app.add_question') }}" class="button-primary">
            <a href="{{ route('guild-apps', $guild->id) }}" class="button-secondary">{{ __('site.cancel') }}</a>
        </div>

    </form>
@endsection
